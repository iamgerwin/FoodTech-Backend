<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Models\Delivery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Orders & Delivery';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('driver_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(50)
                    ->default('pending'),
                Forms\Components\Textarea::make('pickup_address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pickup_latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('pickup_longitude')
                    ->numeric(),
                Forms\Components\Textarea::make('delivery_address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('delivery_latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('delivery_longitude')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('assigned_at'),
                Forms\Components\DateTimePicker::make('pickup_estimated_at'),
                Forms\Components\DateTimePicker::make('picked_up_at'),
                Forms\Components\DateTimePicker::make('delivery_estimated_at'),
                Forms\Components\DateTimePicker::make('delivered_at'),
                Forms\Components\TextInput::make('delivery_fee')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('driver_earning')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('platform_commission')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('delivery_instructions')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('proof_of_delivery')
                    ->maxLength(255),
                Forms\Components\Textarea::make('failure_reason')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pickup_latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pickup_longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pickup_estimated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('picked_up_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_estimated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_fee')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver_earning')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('platform_commission')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proof_of_delivery')
                    ->searchable(),
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
