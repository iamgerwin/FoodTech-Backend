<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Filament\Resources\DriverResource\RelationManagers;
use App\Models\Driver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('license_number')
                    ->maxLength(100),
                Forms\Components\DatePicker::make('license_expiry'),
                Forms\Components\TextInput::make('vehicle_type')
                    ->maxLength(50),
                Forms\Components\TextInput::make('vehicle_plate')
                    ->maxLength(20),
                Forms\Components\TextInput::make('vehicle_model')
                    ->maxLength(100),
                Forms\Components\Toggle::make('is_verified')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Toggle::make('is_available')
                    ->required(),
                Forms\Components\TextInput::make('current_latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('current_longitude')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('last_location_update'),
                Forms\Components\TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(5),
                Forms\Components\TextInput::make('total_deliveries')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_earnings')
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
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('license_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('license_expiry')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_plate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_model')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean(),
                Tables\Columns\TextColumn::make('current_latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_location_update')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_deliveries')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_earnings')
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
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }
}
