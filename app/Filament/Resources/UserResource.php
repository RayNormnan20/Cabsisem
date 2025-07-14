<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 3;

    protected static function getNavigationLabel(): string
    {
        return __('Users');
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
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('first_name')
                                ->label('Nombre')
                                ->required()
                                ->maxLength(100),

                            Forms\Components\TextInput::make('last_name')
                                ->label('Apellidos')
                                ->required()
                                ->maxLength(100),

                            Forms\Components\TextInput::make('phone')
                                ->label('Celular')
                                ->required()
                                ->tel()
                                ->maxLength(15),

                            Forms\Components\TextInput::make('email')
                                ->label('Correo Electrónico')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Forms\Components\TextInput::make('password')
                                ->label('Contraseña')
                                ->password()
                                ->maxLength(255)
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                                ->confirmed(),

                            Forms\Components\TextInput::make('password_confirmation')
                                ->label('Confirmar contraseña')
                                ->password()
                                ->maxLength(255)
                                ->dehydrated(false),

                            Forms\Components\Toggle::make('is_active')
                                ->label('¿Usuario activo?')
                                ->default(true),

                            Forms\Components\Select::make('roles')
                                ->label('Cargos')
                                ->required()
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload()
                                ->searchable(),

                            /* Forms\Components\Select::make('ruta')
                                ->label('Asignar rutas')
                                ->multiple()
                                ->relationship('ruta', 'nombre')
                                ->preload()
                                ->searchable(), */
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Full name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email address'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TagsColumn::make('roles.name')
                    ->label(__('Cargo'))
                    ->limit(2),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('Email verified at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                
                /* Tables\Columns\TextColumn::make('socials')
                    ->label(__('Linked social networks'))
                    ->view('partials.filament.resources.social-icon'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(), */
                
            ])
            
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
