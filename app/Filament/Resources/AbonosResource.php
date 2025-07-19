<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbonosResource\Pages;
use App\Models\Abono;
use App\Models\Abonos;
use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Creditos;
use Illuminate\Support\HtmlString;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Text;
use Filament\Forms\Components\Html;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class AbonosResource extends Resource
{
    protected static ?string $model = Abonos::class;
    protected static ?string $navigationIcon = 'heroicon-o-cash';
    protected static ?int $navigationSort = 1;

    protected static function getNavigationLabel(): string
    {
        return __('Abonos');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Movimientos');
    }

  public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Campos ocultos que deben ser manejados
            Forms\Components\Hidden::make('id_cliente')
                ->required(),
                
            Forms\Components\Hidden::make('id_credito'),
            Forms\Components\Hidden::make('id_ruta'),
            Forms\Components\Hidden::make('id_usuario'),
            
            Forms\Components\TextInput::make('monto_abono')
                ->label('Monto del Abono')
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    if ($get('id_credito') && is_numeric($state)) {
                        $saldoAnterior = $get('saldo_anterior');
                        $set('saldo_posterior', $saldoAnterior - $state);
                    }
                }),
                
            Forms\Components\TextInput::make('saldo_anterior')
                ->label('Saldo Anterior')
                ->numeric()
                ->disabled(),
                
            Forms\Components\TextInput::make('saldo_posterior')
                ->label('Nuevo Saldo')
                ->numeric()
                ->disabled(),
                
           Repeater::make('conceptosabonos')
                ->label('Métodos de Pago')
                ->relationship('conceptosabonos')
                ->schema([
                    Select::make('tipo_concepto') 
                        ->options([
                            'Efectivo' => 'Efectivo',
                            'Transferencia' => 'Transferencia',
                            'Yape' => 'Yape',
                            'Plin' => 'Plin',
                            'Tarjeta' => 'Tarjeta',
                        ])
                        ->required(),
               

                    TextInput::make('monto')
                        ->label('Monto')
                        ->numeric()
                        ->required(),
    
                    Forms\Components\FileUpload::make('foto_comprobante')
                        ->label('Comprobante')
                        ->image()
                        ->directory('comprobantes/abonos')
                        ->visible(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia']))
                        ->required(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia'])),
                        
                    Forms\Components\TextInput::make('referencia')
                        ->label('N° Operación')
                        ->visible(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia']))
                        ->required(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia'])),
                ])
                ->defaultItems(1)
                ->minItems(1)
                ->createItemButtonLabel('Agregar método de pago')
                ->columns(2),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('fecha_pago')
                ->label('Fecha')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            TextColumn::make('usuario.name')
                ->label('Usuario'),
    
            TextColumn::make('cliente.nombre')
                ->label('Cliente')
                ->searchable(),

                TextColumn::make('concepto.nombre')
                    ->label('Concepto'),

                TextColumn::make('credito.tipoPago.nombre')
                    ->label('Forma de Pago')
                    ->searchable(),
                    
                TextColumn::make('monto_abono')
                    ->label('Cantidad')
                    ->money('PEN', true)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cliente')
                    ->relationship('cliente', 'nombre')
                    ->searchable(),
                    
                Tables\Filters\Filter::make('fecha_pago')
                    ->form([
                        Forms\Components\DatePicker::make('desde'),
                        Forms\Components\DatePicker::make('hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_pago', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_pago', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('')
                    ->icon('heroicon-o-pencil-alt')
                    ->color('primary')
                    ->size('lg')
                    ->url(fn ($record): string => static::getUrl('edit', ['record' => $record]))
                    ->extraAttributes([
                        'title' => 'Editar',
                        'class' => 'hover:bg-primary-50 rounded-full'
                    ]),
                
                Tables\Actions\Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->size('lg')
                    ->button()
                    ->modalHeading('Comprobante de Pago')
                    ->form(function ($record) {
                        $comprobante = $record->conceptosabonos->firstWhere('foto_comprobante', '!=', null);
                        
                        if (!$comprobante || !$comprobante->foto_comprobante) {
                            return [
                                \Filament\Forms\Components\Placeholder::make('no_comprobante')
                                    ->content('<div class="p-4 text-center text-gray-500">No hay comprobante disponible</div>')
                                    ->disableLabel()
                            ];
                        }

                        $imageUrl = asset('storage/'.$comprobante->foto_comprobante);
                        $html = <<<HTML
                            <div class="flex justify-center p-4">
                                <img src="$imageUrl" 
                                    class="rounded-lg max-h-[80vh] cursor-pointer"
                                    onclick="window.open(this.src, '_blank')">
                            </div>
                        HTML;

                        return [
                            \Filament\Forms\Components\Card::make()
                                ->schema([
                                    \Filament\Forms\Components\Placeholder::make('comprobante')
                                        ->content(new HtmlString($html))
                                        ->disableLabel()
                                ])
                                ->columnSpanFull()
                        ];
                    })
                    ->modalWidth('4xl')
                    ->modalButton('Cerrar')
                    ->hidden(fn ($record) => !$record->conceptosabonos->where('foto_comprobante', '!=', null)->count())
                    ->extraAttributes([
                        'title' => 'Ver comprobante',
                        'class' => 'hover:bg-success-50 rounded-full'
                    ])
                    ->action(function () {
                        // Acción vacía necesaria para el modal
                    })

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbonos::route('/'),
            'create' => Pages\CreateAbonos::route('/create'),
            'edit' => Pages\EditAbonos::route('/{record}/edit'),
        ];
    }
}