<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OficinasResource\Pages;
use App\Filament\Resources\OficinasResource\RelationManagers;
use App\Models\Oficcina;
use App\Models\Oficina;
use App\Models\Ruta;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OficinasResource extends Resource
{

    protected static ?string $model = Oficina::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';


    protected static ?int $navigationSort = 3;

    protected static function getNavigationLabel(): string
    {
        return __('Oficina');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Permissions');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOficinas::route('/'),
            'create' => Pages\CreateOficinas::route('/create'),
            'edit' => Pages\EditOficinas::route('/{record}/edit'),
        ];
    }
}
