<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Menu Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('restaurant_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('discounted_price')
                    ->numeric(),
                Forms\Components\TextInput::make('preparation_time')
                    ->required()
                    ->numeric()
                    ->default(15),
                Forms\Components\TextInput::make('calories')
                    ->numeric(),
                Forms\Components\Textarea::make('ingredients')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('allergens')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_vegetarian')
                    ->required(),
                Forms\Components\Toggle::make('is_vegan')
                    ->required(),
                Forms\Components\Toggle::make('is_gluten_free')
                    ->required(),
                Forms\Components\Toggle::make('is_spicy')
                    ->required(),
                Forms\Components\TextInput::make('spice_level')
                    ->numeric(),
                Forms\Components\Toggle::make('is_available')
                    ->required(),
                Forms\Components\Toggle::make('is_featured')
                    ->required(),
                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('restaurant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discounted_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preparation_time')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('calories')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_vegetarian')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_vegan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_gluten_free')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_spicy')
                    ->boolean(),
                Tables\Columns\TextColumn::make('spice_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
