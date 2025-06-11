<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProviderResource\Pages;
use App\Filament\Admin\Resources\ProviderResource\RelationManagers;
use App\Models\Provider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;

    protected static ?string $modelLabel = 'Proveedor';
    protected static ?string $pluralModelLabel = 'Proveedores';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de Usuario')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label('Ciudad')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Dirección')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_id')
                    ->label('Identificación Fiscal')
                    ->maxLength(255),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Ciudad')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Dirección')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_id')
                    ->label('RUC')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListProviders::route('/'),
            'create' => Pages\CreateProvider::route('/create'),
            'edit' => Pages\EditProvider::route('/{record}/edit'),
        ];
    }
}
