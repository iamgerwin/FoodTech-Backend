# OnboardingState Enum Documentation

File: `app/Enums/OnboardingState.php`

## Enum Values
- **Pending** (`pending`): User registration is pending approval.
- **Approved** (`approved`): User has been approved and can use the application.
- **Declined** (`declined`): User registration has been declined.

## Methods
### info()
Returns an array with the key, label, and description for each state.

### allInfo()
Returns an array of info objects for all states.

---

### Example Usage
```php
$state = OnboardingState::Pending;
$info = $state->info();
// [
//   'key' => 'pending',
//   'label' => 'Pending',
//   'description' => 'User registration is pending approval.'
// ]
```
