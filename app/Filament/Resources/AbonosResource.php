<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbonosResource\Pages;
use App\Models\Abonos;
use Illuminate\Support\HtmlString;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action; 
use Filament\Tables\Filters\Filter; 
use Filament\Tables\Filters\SelectFilter; 
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
            // Sección de fechas y montos
            Forms\Components\Section::make('Datos del Abono')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('fecha_credito')
                                ->label('Fecha de Crédito')
                                ->disabled()
                                ->dehydrated(false),
                                
                            Forms\Components\TextInput::make('fecha_vencimiento')
                                ->label('Fecha de Vencimiento')
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                        
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('saldo_anterior')
                                ->label('Saldo')
                                ->numeric()
                                ->disabled()
                                ->prefix('S/'),
                                
                            Forms\Components\TextInput::make('valor_cuota')
                                ->label('Cuota')
                                ->numeric()
                                ->disabled()
                                ->prefix('S/'),
                                
                            Forms\Components\TextInput::make('monto_abono')
                                ->label('Abono *')
                                ->numeric()
                                ->required()
                                ->prefix('S/')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    if ($get('id_credito') && is_numeric($state)) {
                                        $saldoAnterior = $get('saldo_anterior');
                                        $set('saldo_posterior', $saldoAnterior - $state);
                                    }
                                }),
                        ]),
                ])
                ->columns(1),
                
            // Campos ocultos
            Forms\Components\Hidden::make('id_cliente')
                ->required(),
                
            Forms\Components\Hidden::make('id_credito'),
            Forms\Components\Hidden::make('id_ruta'),
            Forms\Components\Hidden::make('id_usuario'),
            Forms\Components\Hidden::make('saldo_posterior'),
                
            // Sección de métodos de pago
            Forms\Components\Section::make('Métodos de Pago')
                ->schema([
                    Repeater::make('conceptosabonos')
                        ->label('')
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
                                ->required()
                                ->columnSpan(1),
                               
                            TextInput::make('monto')
                                ->label('Monto')
                                ->numeric()
                                ->required()
                                ->prefix('S/')
                                ->columnSpan(1),
    
                            Forms\Components\FileUpload::make('foto_comprobante')
                                ->label('Comprobante')
                                ->image()
                                ->directory('comprobantes/abonos')
                                ->visible(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia']))
                                ->required(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia']))
                                ->columnSpan(2),
                                
                            Forms\Components\TextInput::make('referencia')
                                ->label('N° Operación')
                                ->visible(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia']))
                                ->required(fn ($get) => in_array($get('tipo_concepto'), ['Yape', 'Plin', 'Transferencia']))
                                ->columnSpan(2),
                        ])
                        ->columns(2)
                        ->defaultItems(1)
                        ->minItems(1)
                        ->createItemButtonLabel('Agregar método de pago'),
                ]),
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
                    
                Filter::make('fecha_pago') // Usando la clase importada directamente
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
                Action::make('edit') // Usando la clase importada directamente
                    ->label('')
                    ->icon('heroicon-o-pencil-alt')
                    ->color('primary')
                    ->size('lg')
                    ->url(fn ($record): string => static::getUrl('edit', ['record' => $record]))
                    ->extraAttributes([
                        'title' => 'Editar',
                        'class' => 'hover:bg-primary-50 rounded-full'
                    ]),
                
                Action::make('view') // Usando la clase importada directamente
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->color(fn ($record) => $record->conceptosabonos->firstWhere('foto_comprobante', '!=', null) ? 'primary' : 'secondary')
                    ->size('sm')
                    ->button()
                    ->modalHeading('Detalles del Abono')
                    // NO HAY withLivewire() AQUÍ. La lógica se moverá al ListAbonos.php
                    ->action(function (Action $action) {
                        // Esta acción simplemente abre el modal. La navegación
                        // se gestiona por el componente de página ListAbonos.
                    })
                    ->form(function ($record) {
                        // Obtener el ID actual y buscar IDs anterior y siguiente
                        $currentId = $record->id_abono;

                        // Obtener todos los abonos con comprobantes para navegación
                        $abonosIds = Abonos::whereHas('conceptosabonos', function ($query) {
                            $query->where('foto_comprobante', '!=', null);
                        })->orderBy('fecha_pago', 'desc')
                            ->pluck('id_abono')
                            ->toArray();

                        $currentIndex = array_search($currentId, $abonosIds);
                        // Asegúrate de pasar 'null' como cadena para JavaScript si el ID es nulo
                        $anteriorId = $currentIndex > 0 ? $abonosIds[$currentIndex - 1] : 'null';
                        $siguienteId = isset($abonosIds[$currentIndex + 1]) ? $abonosIds[$currentIndex + 1] : 'null';

                        $comprobante = $record->conceptosabonos->firstWhere('foto_comprobante', '!=', null);
                        
                        // Información compacta en 3 columnas
                        $infoHtml = <<<HTML
                            <div class="space-y-1 p-2">
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div>
                                        <p class="font-medium text-gray-500">Cliente</p>
                                        <p>{$record->cliente->nombre}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-500">Fecha</p>
                                        <p>{$record->fecha_pago->format('d/m/Y H:i')}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-500">Monto</p>
                                        <p>S/ {$record->monto_abono}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div>
                                        <p class="font-medium text-gray-500">Usuario</p>
                                        <p>{$record->usuario->name}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="font-medium text-gray-500">Métodos de pago</p>
                                        <p>{$record->conceptosabonos->pluck('tipo_concepto')->implode(', ')}</p>
                                    </div>
                                </div>
                            </div>
                        HTML;

                        // Botones de navegación - **¡Emitiendo evento a Livewire!**
                        $anteriorDisabledAttribute = $anteriorId === 'null' ? 'disabled' : '';
                        $siguienteDisabledAttribute = $siguienteId === 'null' ? 'disabled' : '';
                        
                        // Pre-calcular el contenido del span para evitar errores de sintaxis
                        $posicionTexto = "Comprobante " . ($currentIndex + 1) . " de " . count($abonosIds);

                        $navegacionHtml = <<<HTML
                            <div class="flex justify-between items-center mb-2">
                                <button
                                    type="button"
                                    // Livewire.emit dispara un evento que el componente de la página ListAbonos escuchará
                                    onclick="Livewire.emit('goToActionRecord', '{$anteriorId}');"
                                    class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-sm rounded-md"
                                    {$anteriorDisabledAttribute}
                                >
                                    ◀ Anterior
                                </button>
                                <span class="text-sm text-gray-500">{$posicionTexto}</span>
                                <button
                                    type="button"
                                    // Livewire.emit dispara un evento que el componente de la página ListAbonos escuchará
                                    onclick="Livewire.emit('goToActionRecord', '{$siguienteId}');"
                                    class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-sm rounded-md"
                                    {$siguienteDisabledAttribute}
                                >
                                    Siguiente ▶
                                </button>
                            </div>
                        HTML;

                        $components = [
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('navegacion')
                                        ->content(new HtmlString($navegacionHtml))
                                        ->disableLabel(),
                                    Forms\Components\Placeholder::make('info')
                                        ->content(new HtmlString($infoHtml))
                                        ->disableLabel()
                                ])
                                ->columnSpanFull(),
                        ];

                        // Comprobante más compacto si existe
                        if ($comprobante && $comprobante->foto_comprobante) {
                            $imageUrl = asset('storage/' . $comprobante->foto_comprobante);
                            $comprobanteHtml = <<<HTML
                                <div class="space-y-1 p-2">
                                    <p class="text-xs font-medium text-gray-500">Comprobante</p>
                                    <div class="flex justify-center">
                                        <img src="$imageUrl"
                                            class="rounded-lg max-h-[290px] max-w-full object-contain cursor-pointer"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                </div>
                            HTML;

                            $components[] = Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('comprobante')
                                        ->content(new HtmlString($comprobanteHtml))
                                        ->disableLabel()
                                ])
                                ->columnSpanFull();
                        } else {
                            $components[] = Forms\Components\Placeholder::make('no_comprobante')
                                ->content('No hay comprobante disponible')
                                ->disableLabel();
                        }

                        return $components;
                    })
                    ->modalWidth('xl') // Modal más estrecho
                    ->modalButton('Cerrar')
                    ->hidden(fn ($record) => $record->conceptosabonos->count() === 0)
                    ->extraAttributes([
                        'title' => 'Ver Comprobante',
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