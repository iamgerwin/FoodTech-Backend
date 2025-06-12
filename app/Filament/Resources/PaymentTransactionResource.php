<?php

namespace App\Filament\Resources;

use App\Enums\PaymentTypeEnum;
use App\Filament\Resources\PaymentTransactionResource\Pages;
use App\Models\PaymentTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PaymentTransactionResource extends Resource
{
    protected static ?string $model = PaymentTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $modelLabel = 'Payment';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Promotions & Payments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('transaction_id')
                    ->required()
                    ->maxLength(255)
                    ->default(fn () => 'TRX-' . time()),
                
                // Payment Type Selection
                Forms\Components\Select::make('payment_type')
                    ->label('Payment Type')
                    ->options(PaymentTypeEnum::getTypes())
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('payment_subtype', null)),
                
                // Payment Subtype (conditional)
                Forms\Components\Select::make('payment_subtype')
                    ->label('Payment Method')
                    ->options(function (callable $get) {
                        $type = $get('payment_type');
                        if (!$type) {
                            return [];
                        }
                        $subTypes = PaymentTypeEnum::getSubTypes($type);
                        return $subTypes ? array_combine($subTypes, array_map(
                            fn($type) => PaymentTypeEnum::getSubTypeLabels()[$type] ?? $type,
                            $subTypes
                        )) : [];
                    })
                    ->required(fn (callable $get) => in_array($get('payment_type'), [
                        PaymentTypeEnum::E_WALLET->value,
                        PaymentTypeEnum::CARD->value,
                    ]))
                    ->hidden(fn (callable $get) => !in_array($get('payment_type'), [
                        PaymentTypeEnum::E_WALLET->value,
                        PaymentTypeEnum::CARD->value,
                    ])),
                
                // Payment Details (JSON)
                Forms\Components\KeyValue::make('payment_details')
                    ->keyLabel('Field')
                    ->valueLabel('Value')
                    ->columnSpanFull(),
                
                // Amount and Currency
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₱')
                    ->columnSpan(1),
                Forms\Components\Select::make('currency')
                    ->options([
                        'PHP' => 'Philippine Peso (₱)',
                        'USD' => 'US Dollar ($)',
                    ])
                    ->default('PHP')
                    ->required()
                    ->columnSpan(1),
                
                // Status
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
                
                // Additional Info
                Forms\Components\TextInput::make('payment_provider')
                    ->maxLength(100)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('provider_transaction_id')
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                // System Fields
                Forms\Components\DateTimePicker::make('processed_at')
                    ->displayFormat('M j, Y H:i:s')
                    ->timezone('Asia/Manila'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tenant.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type_label')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'E-Wallet' => 'info',
                        'Card' => 'primary',
                        'Cash' => 'success',
                        default => 'gray',
                    })
                    ->searchable(query: function ($query, string $search): \Illuminate\Database\Eloquent\Builder {
                        $types = array_filter(PaymentTypeEnum::getTypes(), 
                            fn($type) => stripos($type, $search) !== false
                        );
                        return $query->whereIn('payment_type', array_keys($types));
                    }),
                Tables\Columns\TextColumn::make('payment_subtype_label')
                    ->label('Method')
                    ->badge()
                    ->searchable(query: function ($query, string $search): \Illuminate\Database\Eloquent\Builder {
                        $subtypes = array_filter(PaymentTypeEnum::getSubTypeLabels(), 
                            fn($label) => stripos($label, $search) !== false
                        );
                        return $query->whereIn('payment_subtype', array_keys($subtypes));
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('PHP', true)
                    ->sortable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'warning',
                        'cancelled' => 'secondary',
                        default => 'info',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('processed_at')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),
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
            // Add any relations here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentTransactions::route('/'),
            'create' => Pages\CreatePaymentTransaction::route('/create'),
            'edit' => Pages\EditPaymentTransaction::route('/{record}/edit'),
        ];
    }
}
