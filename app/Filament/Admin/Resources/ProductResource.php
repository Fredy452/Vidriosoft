<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Get;
use \Filament\Forms\Set;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Inventario';
    protected static ?string $modelLabel = 'Producto';
    protected static ?string $pluralModelLabel = 'Productos';
    protected static ?string $slug = 'products';
    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Información Básica')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('code')
                                        ->label('Código')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->suffixIcon('heroicon-o-qr-code')
                                        ->helperText('Un código único para identificar el producto')
                                        ->maxLength(255),
                                    TextInput::make('name')
                                        ->label('Nombre')
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(2),

                            RichEditor::make('description')
                                ->label('Descripción')
                                ->columnSpanFull(),

                            FileUpload::make('image')
                                ->label('Imagen')
                                ->image()
                                ->directory('products')
                                ->columnSpanFull(),

                            Grid::make()
                                ->schema([
                                    Select::make('category_id')
                                        ->label('Categoría')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload(),

                                    Select::make('provider_id')
                                        ->label('Proveedor')
                                        ->relationship('provider', 'name')
                                        ->searchable()
                                        ->preload(),

                                    Select::make('glass_type_id')
                                        ->label('Tipo de Vidrio')
                                        ->relationship('glassType', 'name')
                                        ->searchable()
                                        ->preload(),
                                ])->columns(3),

                            Toggle::make('is_active')
                                ->label('¿Está activo?')
                                ->default(true),

                            TagsInput::make('features')
                                ->label('Características')
                                ->placeholder('Añade características y presiona Enter')
                                ->helperText('Ej: Color, resistencia, etc.')
                        ]),

                    Wizard\Step::make('Dimensiones')
                        ->icon('heroicon-o-scale')
                        ->schema([
                            Select::make('unit_type')
                                ->label('Tipo de Unidad')
                                ->options([
                                    'm2' => 'Metro cuadrado (m²)',
                                    'm3' => 'Metro cúbico (m³)',
                                    'unidad' => 'Unidad',
                                ])
                                ->default('m2')
                                ->required()
                                ->live(),

                            Grid::make()
                                ->schema([
                                    TextInput::make('width')
                                        ->label('Ancho (metros)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.01)
                                        ->live(debounce: 500)
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            // Si hay ancho y alto, calcular el área
                                            if ($state && $get('height')) {
                                                $set('area', round($state * $get('height'), 2));

                                                // Si es m3 y hay espesor, también actualizar el volumen
                                                if ($get('unit_type') === 'm3' && $get('thickness')) {
                                                    $thicknessInMeters = $get('thickness') / 1000;
                                                    $set('volume', round($state * $get('height') * $thicknessInMeters, 3));
                                                }
                                            }
                                        })
                                        ->visible(fn(Get $get) => in_array($get('unit_type'), ['m2', 'm3'])),

                                    TextInput::make('height')
                                        ->label('Alto (metros)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.01)
                                        ->live(debounce: 500)
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            // Si hay alto y ancho, calcular el área
                                            if ($state && $get('width')) {
                                                $set('area', round($get('width') * $state, 2));

                                                // Si es m3 y hay espesor, también actualizar el volumen
                                                if ($get('unit_type') === 'm3' && $get('thickness')) {
                                                    $thicknessInMeters = $get('thickness') / 1000;
                                                    $set('volume', round($get('width') * $state * $thicknessInMeters, 3));
                                                }
                                            }
                                        })
                                        ->visible(fn(Get $get) => in_array($get('unit_type'), ['m2', 'm3'])),

                                    TextInput::make('thickness')
                                        ->label('Espesor (milímetros)')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.001)
                                        ->live(debounce: 500)
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            // Si es m3 y hay ancho y alto, actualizar el volumen
                                            if ($get('unit_type') === 'm3' && $get('width') && $get('height')) {
                                                // Convertir espesor de mm a m
                                                $thicknessInMeters = $state / 1000;
                                                $set('volume', round($get('width') * $get('height') * $thicknessInMeters, 3));
                                            }
                                        })
                                        ->visible(fn(Get $get) => $get('unit_type') === 'm3'),
                                ])->columns(3),

                            Grid::make()
                                ->schema([
                                    TextInput::make('area')
                                        ->label('Área (m²)')
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->visible(fn(Get $get) => in_array($get('unit_type'), ['m2', 'm3'])),

                                    TextInput::make('volume')
                                        ->label('Volumen (m³)')
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->visible(fn(Get $get) => $get('unit_type') === 'm3'),
                                ])->columns(2),
                        ]),

                    Wizard\Step::make('Precio y Stock')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('price')
                                        ->label('Precio Base')
                                        ->required()
                                        ->numeric()
                                        ->prefix('₲')
                                        ->minValue(0),

                                    TextInput::make('price_per_unit')
                                        ->label(function (Get $get) {
                                            $unit = $get('unit_type');
                                            return 'Precio por ' . match ($unit) {
                                                'm2' => 'm²',
                                                'm3' => 'm³',
                                                default => 'unidad',
                                            };
                                        })
                                        ->numeric()
                                        ->prefix('₲')
                                        ->minValue(0),

                                    TextInput::make('discount')
                                        ->label('Descuento (%)')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ])->columns(3),

                            Placeholder::make('price_calculation')
                                ->label('Precio Final (con descuento)')
                                ->content(function (Get $get) {
                                    $price = floatval($get('price') ?: 0);
                                    $discount = floatval($get('discount') ?: 0);
                                    return '₲' . number_format($price * (1 - $discount / 100), 0);
                                }),

                            Grid::make()
                                ->schema([
                                    TextInput::make('stock')
                                        ->label(function (Get $get) {
                                            $unit = $get('unit_type');
                                            return 'Stock disponible en ' . match ($unit) {
                                                'm2' => 'm²',
                                                'm3' => 'm³',
                                                default => 'unidades',
                                            };
                                        })
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0),

                                    TextInput::make('min_stock')
                                        ->label('Stock Mínimo')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0),
                                ])->columns(2),
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->circular(false)
                    ->square()
                    ->defaultImageUrl(url('/images/default-product.png'))
                    ->visibility('public'),

                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('unit_type')
                    ->label('Unidad')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'm2' => 'Metro²',
                        'm3' => 'Metro³',
                        'unidad' => 'Unidad',
                        default => $state,
                    })
                    ->colors([
                        'primary' => fn(string $state): bool => $state === 'unidad',
                        'success' => fn(string $state): bool => $state === 'm2',
                        'warning' => fn(string $state): bool => $state === 'm3',
                    ]),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Precio Base')
                    ->money('PYG')
                    ->sortable(),

                TextColumn::make('price_per_unit')
                    ->label('Precio/Unidad')
                    ->money('PYG')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(
                        fn(string $state, Product $record): string =>
                        $state <= $record->min_stock
                            ? 'danger'
                            : ($state <= $record->min_stock * 1.5
                                ? 'warning'
                                : 'success')
                    ),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('provider.name')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('glassType.name')
                    ->label('Tipo de Vidrio')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('discount')
                    ->label('Descuento')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('provider_id')
                    ->label('Proveedor')
                    ->relationship('provider', 'name'),

                Tables\Filters\SelectFilter::make('glass_type_id')
                    ->label('Tipo de Vidrio')
                    ->relationship('glassType', 'name'),

                Tables\Filters\SelectFilter::make('unit_type')
                    ->label('Unidad')
                    ->options([
                        'm2' => 'Metro cuadrado (m²)',
                        'm3' => 'Metro cúbico (m³)',
                        'unidad' => 'Unidad',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('¿Activo?'),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Stock bajo mínimo')
                    ->query(fn(Builder $query): Builder =>
                    $query->whereColumn('stock', '<=', 'min_stock')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                    Tables\Actions\BulkAction::make('toggle_active')
                        ->label('Cambiar estado')
                        ->icon('heroicon-o-check-circle')
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                $record->is_active = !$record->is_active;
                                $record->save();
                            }
                        }),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
