<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientesResource\Pages;
use App\Models\Clientes;
use App\Models\TipoDocumento;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ClientesResource extends Resource
{
    protected static ?string $model = Clientes::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;

    protected static function getNavigationLabel(): string
    {
        return __('Clientes');
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
                    // Sección 1: Información personal
                    Section::make('Información Personal')
                        ->schema([
                            Select::make('id_tipo_documento')
                                ->label('Tipo de Documento')
                                ->options(function () {
                                    return TipoDocumento::query()
                                        ->orderBy('nombre')
                                        ->pluck('nombre', 'id_tipo_documento');
                                })
                                ->required()
                                ->searchable()
                                ->preload(), // Esto mejora el rendimiento con muchos registros

                            TextInput::make('numero_documento')
                                ->label('No. de Documento')
                                ->required()
                                ->maxLength(20),

                            TextInput::make('nombre')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('apellido')
                                ->required()
                                ->maxLength(100),
                        ])->columns(2),

                    // Sección 2: Información de contacto
                    Section::make('Información de Contacto')
                        ->schema([
                            TextInput::make('celular')
                                ->tel()
                                ->maxLength(20),

                            TextInput::make('telefono')
                                ->tel()
                                ->maxLength(20),

                            TextInput::make('direccion')
                                ->label('Dirección')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('direccion2')
                                ->label('Dirección 2')
                                ->maxLength(255),
                        ])->columns(2),

                    // Sección 3: Información adicional
                    Section::make('Información Adicional')
                        ->schema([
                            TextInput::make('ciudad')
                                ->maxLength(100),

                            TextInput::make('nombre_negocio')
                                ->maxLength(100),

                            Toggle::make('activo')
                                ->label('Cliente Activo')
                                ->default(true)
                                ->inline(false),

                            Toggle::make('crear_credito')
                                ->label('Crear crédito después de guardar')
                                ->inline(false),
                        ])->columns(2),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_cliente')
                    ->label('#')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nombre_completo')
                    ->label('Nombre')
                    ->searchable(['nombre', 'apellido'])
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('numero_documento')
                    ->label('Documento')
                    ->searchable(),

                TextColumn::make('nombre_negocio')
                    ->label('Negocio')
                    ->searchable(),

                TextColumn::make('celular')
                    ->searchable(),

                BadgeColumn::make('activo')
                    ->label('Estado')
                    ->enum([
                        true => 'Activo',
                        false => 'Inactivo'
                    ])
                    ->colors([
                        'success' => true,
                        'danger' => false
                    ]),
            ])
            ->filters([
                SelectFilter::make('activo')
                    ->label('Estado')
                    ->options([
                        true => 'Activos',
                        false => 'Inactivos'
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-s-pencil')
                    ->color('primary'),

                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-s-eye')
                    ->color('secondary'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateClientes::route('/create'),
         //   'view' => Pages\ViewClientes::route('/{record}'),
            'edit' => Pages\EditClientes::route('/{record}/edit'),
        ];
    }
}
