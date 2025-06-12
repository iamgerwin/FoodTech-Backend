<?php

namespace App\Enums;

enum PaymentTypeEnum: string
{
    case E_WALLET = 'e-wallet';
    case CASH = 'cash';
    case CARD = 'card';

    public static function getSubTypes(string $type): ?array
    {
        return match ($type) {
            self::E_WALLET->value => [
                'gcash',
                'maya',
                'coins.ph',
            ],
            self::CARD->value => [
                'debit',
                'credit',
            ],
            default => null,
        };
    }

    public static function getTypes(): array
    {
        return [
            self::E_WALLET->value => 'E-Wallet',
            self::CASH->value => 'Cash',
            self::CARD->value => 'Card',
        ];
    }

    public static function getSubTypeLabels(): array
    {
        return [
            'gcash' => 'GCash',
            'maya' => 'Maya',
            'coins.ph' => 'Coins.ph',
            'debit' => 'Debit Card',
            'credit' => 'Credit Card',
        ];
    }
}
