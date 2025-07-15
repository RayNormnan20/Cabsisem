<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditosResource\Pages;
use App\Models\Clientes;
use App\Models\Creditos;
use App\Models\OrdenCobro;
use App\Models\TipoPago;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CreditosResource extends Resource
{
    protected static ?string $model = Creditos::class;
    protected static ?string $navigationIcon = 'heroicon-o-office-building';
    protected static ?int $navigationSort = 3;

    protected static function getNavigationLabel(): string
    {
        return __('Listar Creditos');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Créditos');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    // Grid para dividir en dos columnas
                    Forms\Components\Grid::make(2)
                        ->schema([
                            // Columna izquierda (Datos de entrada)
                            Forms\Components\Group::make([
                                Forms\Components\Section::make('')
                                    ->schema([
                                    Select::make('id_cliente')
                                        ->label('Cliente *')
                                        ->options(function () {
                                            $clienteId = request()->query('cliente_id');

                                            if ($clienteId) {
                                                $cliente = Clientes::find($clienteId);
                                                if ($cliente) {
                                                    return [$cliente->id_cliente => $cliente->nombre_completo];
                                                }
                                            }

                                            return Clientes::query()
                                                ->where('activo', true)
                                                ->get()
                                                ->pluck('nombre_completo', 'id_cliente');
                                        })
                                        ->default(function () {
                                            $clienteId = request()->query('cliente_id');
                                            return Clientes::where('activo', true)->where('id_cliente', $clienteId)->exists() ? $clienteId : null;
                                        })
                                        ->required()
                                        ->searchable()
                                        ->columnSpanFull(),

                                        DatePicker::make('fecha_credito')
                                            ->label('Fecha del Crédito *')
                                            ->default(now())
                                            ->required()
                                            ->displayFormat('d/m/Y')
                                            ->columnSpanFull(),

                                        TextInput::make('valor_credito')
                                            ->label('Valor del Crédito *')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->columnSpanFull()
                                            ->helperText('Por favor ingresa el valor de Crédito'),

                                        TextInput::make('porcentaje_interes')
                                            ->label('Porcentaje *')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->columnSpanFull(),

                                        Select::make('forma_pago')
                                            ->label('Forma de Pago *')
                                            ->options(TipoPago::where('activo', true)->pluck('nombre', 'id_forma_pago'))
                                            ->required()
                                            ->searchable()
                                            ->columnSpanFull(),

                                        TextInput::make('dias_plazo')
                                            ->label('Días *')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->columnSpanFull(),

                                        Select::make('orden_cobro')
                                            ->label('Orden de Cobro')
                                            ->options(OrdenCobro::where('activo', true)->pluck('nombre', 'id_orden_cobro'))
                                            ->default(2) // Asumiendo que 2 es "Último"
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                            // Columna derecha (Resultados calculados)
                            Forms\Components\Group::make([
                                Forms\Components\Section::make('')
                                    ->schema([
                                        TextInput::make('saldo_actual')
                                            ->label('Saldo')
                                            ->numeric()
                                            ->disabled()
                                            ->columnSpanFull(),

                                        TextInput::make('valor_cuota')
                                            ->label('Valor de la Cuota')
                                            ->numeric()
                                            ->disabled()
                                            ->columnSpanFull(),

                                        TextInput::make('numero_cuotas')
                                            ->label('No. de Cuotas')
                                            ->numeric()
                                            ->disabled()
                                            ->columnSpanFull(),

                                        DatePicker::make('fecha_vencimiento')
                                            ->label('Fecha de Vencimiento')
                                            ->disabled()
                                            ->displayFormat('d/m/Y')
                                            ->columnSpanFull(),

                                        DatePicker::make('fecha_proximo_pago')
                                            ->label('Fecha de Próximo Pago')
                                            ->disabled()
                                            ->displayFormat('d/m/Y')
                                            ->columnSpanFull(),

                                         Forms\Components\Repeater::make('conceptosCredito')
                                                ->label('Desglose del Crédito')
                                                ->relationship() // esto asume que tu modelo tiene ->conceptosCredito()
                                                ->schema([
                                                    Select::make('tipo_concepto')
                                                        ->label('Tipo de Concepto')
                                                        ->options([
                                                            'Efectivo' => 'Efectivo',
                                                            'Yape' => 'Yape',
                                                            'Caja' => 'Caja',
                                                            'Saldo renovación' => 'Saldo renovación',
                                                            'Abono para completar préstamo' => 'Abono para completar préstamo',
                                                        ])
                                                        ->required()
                                                        ->reactive(), // Para mostrar/ocultar foto_comprobante según valor

                                                    TextInput::make('monto')
                                                        ->label('Monto')
                                                        ->numeric()
                                                        ->required(),

                                                    Forms\Components\FileUpload::make('foto_comprobante')
                                                        ->label('Comprobante Yape')
                                                        ->visible(fn ($get) => $get('tipo_concepto') === 'Yape')
                                                        ->directory('comprobantes/yape')
                                                        ->image()
                                                        ->maxSize(2048)
                                                        ->columnSpanFull()
                                                ])
                                                ->defaultItems(1)
                                                ->minItems(1)
                                                ->createItemButtonLabel('Agregar concepto')
                                                ->columns(2),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('fecha_credito')
                ->label('Fecha Crédito')
                ->date('d/m/Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('valor_credito')
                ->label('Valor')
                ->sortable(),

            Tables\Columns\TextColumn::make('porcentaje_interes')
                ->label('Interés')
                ->suffix('%')
                ->sortable(),

            Tables\Columns\TextColumn::make('numero_cuotas')
                ->label('Nr. cuotas')
                ->sortable(),

            Tables\Columns\TextColumn::make('valor_cuota')
                ->label('Cuota')
                ->sortable(),

            Tables\Columns\TextColumn::make('saldo_actual')
                ->label('Saldo')
                ->sortable(),

            Tables\Columns\TextColumn::make('fecha_vencimiento')
                ->label('Vencimiento')
                ->date('d/m/Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('conceptosCredito')
                ->label('Detalle Entrega')
                ->formatStateUsing(function ($record) {
                    return $record->conceptosCredito
                        ->map(fn ($c) => "{$c->tipo_concepto}: S/ " . number_format($c->monto, 2))
                        ->join(' | ');
                })
                ->wrap() // para que no se desborde si es muy largo
                ->searchable(false),

/*
                Tables\Columns\TextColumn::make('fecha_vencimiento')
                ->label('último Pago')
                ->date('d/m/Y')
                ->sortable(),
                */

            Tables\Columns\BadgeColumn::make('estado')
                ->label('Estado')
                ->getStateUsing(fn ($record) => $record->saldo_actual > 0 ? 'Activo' : 'Pagado')
                ->colors([
                    'success' => 'Activo',
                    'danger' => 'Pagado',
                ]),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('forma_pago')
                ->label('Forma de Pago')
                ->relationship('tipoPago', 'nombre'),

            Tables\Filters\SelectFilter::make('orden_cobro')
                ->label('Orden de Cobro')
                ->relationship('ordenCobro', 'nombre'),

            Tables\Filters\Filter::make('activos')
                ->label('Solo créditos activos')
                ->query(fn ($query) => $query->where('saldo_actual', '>', 0)),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->icon('heroicon-s-pencil')
                ->color('primary'),

            Tables\Actions\ViewAction::make()
                ->icon('heroicon-s-eye')
                ->color('secondary'),

            Tables\Actions\DeleteAction::make()
                ->icon('heroicon-s-trash')
                ->color('danger'),
        ])

        ->bulkActions([

        ]);

}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreditos::route('/'),
            'create' => Pages\CreateCreditos::route('/create'),
            'edit' => Pages\EditCreditos::route('/{record}/edit'),
         //   'view' => Pages\ViewCredito::route('/{record}'),

        ];
    }
}