<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresosGastosResource\Pages;
use App\Models\Abonos;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class IngresosGastosResource extends Resource
{
    protected static ?string $model = Abonos::class; // Cambiamos al modelo Abonos
    protected static ?string $navigationIcon = 'heroicon-o-cash';
    protected static ?int $navigationSort = 2;
    
    protected static ?string $slug = 'movimientos/ingresos-gastos';
    protected static ?string $navigationLabel = 'Ingresos y Gastos';
    protected static ?string $modelLabel = 'Movimiento';
    protected static ?string $pluralModelLabel = 'Ingresos y Gastos';
    protected static ?string $navigationGroup = 'Movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_concepto')
                    ->label('Concepto')
                    ->relationship('concepto', 'nombre')
                    ->required()
                    ->searchable(),
                    
                Forms\Components\Select::make('id_cliente')
                    ->label('Cliente')
                    ->relationship('cliente', 'nombre')
                    ->required()
                    ->searchable(),
                    
                Forms\Components\DateTimePicker::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->required()
                    ->default(now()),
                    
                Forms\Components\TextInput::make('monto_abono')
                    ->label('Monto')
                    ->required()
                    ->numeric()
                    ->prefix('S/'),
                    
                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull(),
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
                    
                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable(),
                    
                TextColumn::make('concepto.nombre')
                    ->label('Concepto')
                    ->searchable(),
                    
                TextColumn::make('monto_abono')
                    ->label('Monto')
                    ->money('PEN', true)
                    ->sortable(),
                    
                BadgeColumn::make('concepto.tipo')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'Ingreso',
                        'danger' => 'Gasto',
                    ])
                    ->sortable(),
                    
                TextColumn::make('usuario.name')
                    ->label('Registrado por'),
            ])
            ->filters([
                SelectFilter::make('concepto')
                    ->label('Concepto')
                    ->relationship('concepto', 'nombre')
                    ->searchable(),
                    
                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'Ingreso' => 'Ingresos',
                        'Gasto' => 'Gastos',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('concepto', function($q) use ($data) {
                                $q->where('tipo', $data['value']);
                            });
                        }
                    }),
                    
                Tables\Filters\Filter::make('fecha_pago')
                    ->form([
                        Forms\Components\DatePicker::make('desde'),
                        Forms\Components\DatePicker::make('hasta'),
                    ])
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('fecha_pago', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Aquí puedes agregar RelationManagers si necesitas
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngresosGastos::route('/'),
            'create' => Pages\CreateIngresosGastos::route('/create'),
            'edit' => Pages\EditIngresosGastos::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['concepto', 'cliente', 'usuario'])
            ->whereHas('concepto'); // Solo abonos con concepto asociado
    }
}