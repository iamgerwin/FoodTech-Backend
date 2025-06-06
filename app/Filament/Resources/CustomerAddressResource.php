<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerAddressResource\Pages;
use App\Filament\Resources\CustomerAddressResource\RelationManagers;
use App\Models\CustomerAddress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerAddressResource extends Resource
{
    protected static ?string $model = CustomerAddress::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('label')
                    ->maxLength(100),
                Forms\Components\Textarea::make('address_line1')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('address_line2')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('state')
                    ->maxLength(100),
                Forms\Components\TextInput::make('postal_code')
                    ->maxLength(20),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(100)
                    ->default('Philippines'),
                Forms\Components\TextInput::make('latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('longitude')
                    ->numeric(),
                Forms\Components\Textarea::make('delivery_instructions')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_default')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean(),
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
            'index' => Pages\ListCustomerAddresses::route('/'),
            'create' => Pages\CreateCustomerAddress::route('/create'),
            'edit' => Pages\EditCustomerAddress::route('/{record}/edit'),
        ];
    }
}
