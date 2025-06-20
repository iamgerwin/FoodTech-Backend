<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Jobs\ImportDriversJob;
use App\Models\Driver;
use App\Enums\OnboardingState;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tenant_id')
                    ->label('Tenant')
                    ->options(\App\Models\Tenant::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(\App\Models\User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
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
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('user.onboarding_state')
                    ->label('Onboarding State')
                    ->formatStateUsing(fn($state) => OnboardingState::tryFrom($state)?->info()['label'] ?? $state)
                    ->colors([
                        'primary' => OnboardingState::Pending->value,
                        'success' => OnboardingState::Approved->value,
                        'danger' => OnboardingState::Declined->value,
                    ])
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
            ->headerActions([
                Action::make('importDrivers')
                    ->label('Import Drivers')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('download_template')
                                ->label('Download sample .csv template')
                                ->url('/driver_import_template.csv', shouldOpenInNewTab: true)
                                ->color('primary')
                                ->icon('heroicon-o-arrow-down-tray'),
                        ]),
                        Forms\Components\FileUpload::make('import_file')
                            ->label('Import File')
                            ->acceptedFileTypes(['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                            ->directory('import/uploads')
                            ->disk('public')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        try {
                            $filePath = $data['import_file'];
                            $permanentPath = 'import/uploads/'.basename($filePath);
                            if ($filePath !== $permanentPath) {
                                \Storage::disk('public')->move($filePath, $permanentPath);
                                \Storage::disk('public')->copy($filePath, $permanentPath);
                            }
                            \Log::info('[Driver Import] Permanent file path:', ['permanentPath' => $permanentPath]);
                            \Log::info('[Driver Import] File exists after move:', ['exists' => \Storage::disk('public')->exists($permanentPath)]);
                            ImportDriversJob::dispatch($permanentPath, auth()->user());
                            Notification::make()
                                ->title('Import started')
                                ->body('Your import is being processed in the background. You will be notified when it completes or if there are any errors.')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Import failed')
                                ->body('Failed to queue import: '.$e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
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
