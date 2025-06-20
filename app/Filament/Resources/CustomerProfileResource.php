<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerProfileResource\Pages;
use App\Models\CustomerProfile;
use App\Enums\OnboardingState;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerProfileResource extends Resource
{
    protected static ?string $model = CustomerProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('date_of_birth'),
                Forms\Components\TextInput::make('gender')
                    ->maxLength(20),
                Forms\Components\TextInput::make('loyalty_points')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_orders')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_spent')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('preferred_payment_method')
                    ->maxLength(50),
                Forms\Components\Textarea::make('dietary_preferences')
                    ->columnSpanFull(),
                Forms\Components\Select::make('user.onboarding_state')
                    ->label('Onboarding State')
                    ->options(collect(OnboardingState::cases())->mapWithKeys(fn($state) => [
                        $state->value => $state->info()['label']
                    ])->toArray())
                    ->required()
                    ->relationship('user', 'onboarding_state'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('primary_address')
                    ->label('Primary Address')
                    ->getStateUsing(function ($record) {
                        $address = $record->addresses->first();
                        if (!$address) return '-';
                        return $address->address_line1 . ', ' . $address->city . ', ' . $address->country;
                    }),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('user.onboarding_state')
                    ->label('Onboarding State')
                    ->formatStateUsing(fn($state) => OnboardingState::tryFrom($state)?->info()['label'] ?? $state)
                    ->colors([
                        'primary' => OnboardingState::Pending->value,
                        'success' => OnboardingState::Approved->value,
                        'danger' => OnboardingState::Declined->value,
                    ]),
                Tables\Columns\TextColumn::make('loyalty_points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_orders')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_spent')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preferred_payment_method')
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
            'index' => Pages\ListCustomerProfiles::route('/'),
            'create' => Pages\CreateCustomerProfile::route('/create'),
            'edit' => Pages\EditCustomerProfile::route('/{record}/edit'),
        ];
    }
}
