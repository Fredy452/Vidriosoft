<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // Definimos el nombre del modelo en singular y en plural para la interfaz de usuario
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre'),
                TextInput::make('email')
                    ->required()
                    ->unique(User::class, 'email', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Este correo electrónico ya está registrado en el sistema',
                    ])
                    ->email()
                    ->maxLength(255)
                    ->label('Correo Electrónico'),
                TextInput::make('phone')
                    ->maxLength(255)
                    ->label('Teléfono'),
                TextInput::make('city')
                    ->maxLength(255)
                    ->label('Ciudad'),
                TextInput::make('address')
                    ->maxLength(255)
                    ->label('Dirección'),
                Toggle::make('is_admin')
                    ->label('¿Es Administrador?'),
                Toggle::make('is_active')
                    ->label('¿Está Activo?')
                    ->default(true),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nombre'),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Correo Electrónico'),
                TextColumn::make('phone')
                    ->sortable()
                    ->searchable()
                    ->label('Teléfono'),
                TextColumn::make('city')
                    ->sortable()
                    ->searchable()
                    ->label('Ciudad'),
                TextColumn::make('address')
                    ->sortable()
                    ->searchable()
                    ->label('Dirección'),
                Tables\Columns\BooleanColumn::make('is_admin')
                    ->label('¿Es Administrador?'),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('¿Está Activo?'),
            ])
            ->filters([
                // Filtros personalizados
                SelectFilter::make('is_admin')
                    ->label('Administrador')
                    ->options([
                        true => 'Sí',
                        false => 'No',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
