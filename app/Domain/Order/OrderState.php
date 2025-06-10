<?php

namespace App\Domain\Order;

class OrderState
{
    // Order state constants
    const INITIATED = 'initiated';

    const RECEIVED_BY_RESTAURANT = 'received_by_restaurant';

    const PREPARING = 'preparing';

    const FINDING_DRIVER = 'finding_driver';

    const PREPARED = 'prepared';

    const HANDED_TO_DRIVER = 'handed_to_driver';

    const IN_TRANSIT = 'in_transit';

    const WAITING_FOR_CUSTOMER = 'waiting_for_customer';

    const RECEIVED_BY_CUSTOMER = 'received_by_customer';

    const CONFIRMED_AND_FEEDBACK = 'confirmed_and_feedback';

    const REFUND_REQUESTED = 'refund_requested';

    const COMPLAINT_RECEIVED = 'complaint_received';

    const REFUND_APPROVED = 'refund_approved';

    const REFUND_DECLINED = 'refund_declined';

    const DRIVER_RETURNING = 'driver_returning';

    const RESTAURANT_REPREPARING = 'restaurant_repreparing';

    const RESTAURANT_DRIVER_EXCHANGE = 'restaurant_driver_exchange';

    const DRIVER_INTRANSIT_NTH = 'driver_intransit_nth';

    const CUSTOMER_FULFILLED = 'customer_fulfilled';

    // Allowed state transitions (order-specific)
    public static array $transitions = [
        self::INITIATED => [self::RECEIVED_BY_RESTAURANT],
        self::RECEIVED_BY_RESTAURANT => [self::PREPARING],
        self::PREPARING => [self::FINDING_DRIVER],
        self::FINDING_DRIVER => [self::PREPARED],
        self::PREPARED => [self::HANDED_TO_DRIVER],
        self::HANDED_TO_DRIVER => [self::IN_TRANSIT],
        self::IN_TRANSIT => [self::WAITING_FOR_CUSTOMER],
        self::WAITING_FOR_CUSTOMER => [self::RECEIVED_BY_CUSTOMER],
        self::RECEIVED_BY_CUSTOMER => [self::CONFIRMED_AND_FEEDBACK, self::REFUND_REQUESTED, self::COMPLAINT_RECEIVED],
        self::REFUND_REQUESTED => [self::REFUND_APPROVED, self::REFUND_DECLINED],
        self::REFUND_APPROVED => [self::DRIVER_RETURNING],
        self::DRIVER_RETURNING => [self::RESTAURANT_REPREPARING],
        self::RESTAURANT_REPREPARING => [self::RESTAURANT_DRIVER_EXCHANGE],
        self::RESTAURANT_DRIVER_EXCHANGE => [self::DRIVER_INTRANSIT_NTH],
        self::DRIVER_INTRANSIT_NTH => [self::CUSTOMER_FULFILLED],
        self::CONFIRMED_AND_FEEDBACK => [self::CUSTOMER_FULFILLED],
        self::REFUND_DECLINED => [self::CUSTOMER_FULFILLED],
        self::COMPLAINT_RECEIVED => [self::REFUND_APPROVED, self::REFUND_DECLINED],
    ];
}
