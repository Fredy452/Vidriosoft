<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use \Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationGroup = 'Inventario';
    protected static ?string $modelLabel = 'Categoría';
    protected static ?string $pluralModelLabel = 'Categorías';

    protected static ?string $navigationIcon = 'heroicon-o-tag';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre de la Categoría')
                            ->maxLength(255)
                            ->live(debounce: 500) // Actualiza en tiempo real mientras se escribe
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(true)
                            ->unique(Category::class, 'slug', ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpan(2),
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre de la Categoría'),
                TextColumn::make('slug')->label('Slug'),
                TextColumn::make('description')->label('Descripción'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
