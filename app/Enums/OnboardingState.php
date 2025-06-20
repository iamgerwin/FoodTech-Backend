<?php

namespace App\Enums;

enum OnboardingState: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Declined = 'declined';

    /**
     * Get key, label, description for each state
     */
    public function info(): array
    {
        return match ($this) {
            self::Pending => [
                'key' => self::Pending->value,
                'label' => 'Pending',
                'description' => 'User registration is pending approval.'
            ],
            self::Approved => [
                'key' => self::Approved->value,
                'label' => 'Approved',
                'description' => 'User has been approved and can use the application.'
            ],
            self::Declined => [
                'key' => self::Declined->value,
                'label' => 'Declined',
                'description' => 'User registration has been declined.'
            ],
        };
    }

    /**
     * Get all states as array of info objects
     */
    public static function allInfo(): array
    {
        return array_map(fn($state) => $state->info(), self::cases());
    }
}
