<?php

namespace App\Filament\Resources\RestaurantBranchResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BranchMenuItemOverrideRelationManager extends RelationManager
{
    protected static string $relationship = 'branchMenuItemOverrides';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('menu_item_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('custom_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('custom_price')
                    ->numeric(),
                Forms\Components\Textarea::make('custom_description'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('custom_name')
            ->columns([
                Tables\Columns\TextColumn::make('effective_name')
                    ->label('Name')
                    ->getStateUsing(fn ($record) => $record->custom_name ?? $record->menuItem->name)
                    ->description(fn ($record) => $record->custom_name ? 'Overridden' : 'Default')
                    ->color(fn ($record) => $record->custom_name ? 'primary' : 'gray'),
                Tables\Columns\TextColumn::make('effective_price')
                    ->label('Price')
                    ->getStateUsing(fn ($record) => $record->custom_price ?? $record->menuItem->price)
                    ->money('PHP')
                    ->description(fn ($record) => $record->custom_price ? 'Overridden' : 'Default')
                    ->color(fn ($record) => $record->custom_price ? 'primary' : 'gray'),
                Tables\Columns\IconColumn::make('is_overridden')
                    ->label('Overridden')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->custom_name || $record->custom_price),
                Tables\Columns\TextColumn::make('menuItem.name')->label('Default Name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('custom_name')->label('Custom Name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('custom_price')->label('Custom Price')->money('PHP')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('overridden')
                    ->label('Overridden Only')
                    ->query(fn ($query) => $query->whereNotNull('custom_name')->orWhereNotNull('custom_price')),
                Tables\Filters\Filter::make('default')
                    ->label('Default Only')
                    ->query(fn ($query) => $query->whereNull('custom_name')->whereNull('custom_price')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('reset_to_default')
                        ->label('Reset to Default')
                        ->action(fn ($records) => $records->each->update(['custom_name' => null, 'custom_price' => null, 'custom_description' => null]))
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
