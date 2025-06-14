<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GlassTypeResource\Pages;
use App\Filament\Admin\Resources\GlassTypeResource\RelationManagers;
use App\Models\GlassType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Section;

class GlassTypeResource extends Resource
{
    protected static ?string $model = GlassType::class;

    protected static ?string $navigationGroup = 'Inventario';
    protected static ?string $navigationLabel = 'Tipos de Vidrio';
    protected static ?string $slug = 'glass-types';

    protected static ?string $modelLabel = 'Tipo de Vidrio';
    protected static ?string $pluralModelLabel = 'Tipos de Vidrio';

    protected static ?string $navigationIcon = 'heroicon-o-window';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información Básica')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('description')
                    ->label('Descripción'),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListGlassTypes::route('/'),
            'create' => Pages\CreateGlassType::route('/create'),
            'edit' => Pages\EditGlassType::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
