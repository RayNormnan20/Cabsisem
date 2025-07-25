<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YapeClienteResource\Pages;
use App\Models\YapeCliente;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class YapeClienteResource extends Resource
{
    protected static ?string $model = YapeCliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Yape Clientes';
    protected static ?string $modelLabel = 'Yape Cliente';

   public static function form(Form $form): Form
{
    return $form
        ->schema([
           Forms\Components\Select::make('id_cliente') // Cambiado de 'cliente_id' a 'id_cliente'
    ->label('Cliente')
    ->options(function () {
        $user = Auth::user();
        $ruta = $user->rutaPrincipal;

        if (!$ruta) {
            return [];
        }

        return \App\Models\Clientes::listarPorRuta($ruta->id_ruta);
    })
    ->searchable()
    ->required(),

            Forms\Components\TextInput::make('nombre')
                ->required()
                ->label('Nombre del que Yapea'),

                Forms\Components\Select::make('user_id')
                    ->default(fn () => Auth::id())
                    ->disabled()
                    ->label('Cobrador'),

            Forms\Components\TextInput::make('monto')
                ->numeric()
                ->required()
                ->label('Monto'),

            Forms\Components\TextInput::make('entregar')
                ->numeric()
                ->label('Entregar'),


        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre Yape'),

                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Cobrador'),

                Tables\Columns\TextColumn::make('monto')
                    ->money('PEN',true)
                    ->label('Monto'),

                Tables\Columns\TextColumn::make('entregar')
                    ->money('PEN', true)
                    ->label('Entregar'),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de Registro'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListYapeClientes::route('/'),
            'create' => Pages\CreateYapeCliente::route('/create'),
            'edit' => Pages\EditYapeCliente::route('/{record}/edit'),
        ];
    }
}