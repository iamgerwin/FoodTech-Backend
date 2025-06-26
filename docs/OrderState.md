# OrderState Class Documentation

File: `app/Domain/Order/OrderState.php`

## State Constants
- **INITIATED**: `initiated`
- **RECEIVED_BY_RESTAURANT**: `received_by_restaurant`
- **PREPARING**: `preparing`
- **FINDING_DRIVER**: `finding_driver`
- **PREPARED**: `prepared`
- **HANDED_TO_DRIVER**: `handed_to_driver`
- **IN_TRANSIT**: `in_transit`
- **WAITING_FOR_CUSTOMER**: `waiting_for_customer`
- **RECEIVED_BY_CUSTOMER**: `received_by_customer`
- **CONFIRMED_AND_FEEDBACK**: `confirmed_and_feedback`
- **REFUND_REQUESTED**: `refund_requested`
- **COMPLAINT_RECEIVED**: `complaint_received`
- **REFUND_APPROVED**: `refund_approved`
- **REFUND_DECLINED**: `refund_declined`
- **DRIVER_RETURNING**: `driver_returning`
- **RESTAURANT_REPREPARING**: `restaurant_repreparing`
- **RESTAURANT_DRIVER_EXCHANGE**: `restaurant_driver_exchange`
- **DRIVER_INTRANSIT_NTH**: `driver_intransit_nth`
- **CUSTOMER_FULFILLED**: `customer_fulfilled`

## State Transitions
The `$transitions` static array defines allowed state transitions for orders. Example:
```php
OrderState::$transitions[OrderState::INITIATED]; // ['received_by_restaurant']
```

---

### Example Usage
```php
$nextStates = OrderState::$transitions[OrderState::PREPARING];
// ['finding_driver']
```
