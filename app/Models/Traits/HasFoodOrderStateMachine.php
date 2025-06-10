<?php

namespace App\Models\Traits;

trait HasFoodOrderStateMachine
{
    /**
     * Map of allowed state transitions. Override in the model.
     * Example: [ 'pending' => ['approved', 'rejected'], ... ]
     */
    public static array $stateTransitions = [];

    /**
     * The attribute name that stores the state. Override in the model if needed.
     */
    protected string $stateAttribute = 'status';

    /**
     * Check if the model can transition to a new state.
     */
    public function canTransitionTo($newState): bool
    {
        $current = $this->{$this->stateAttribute};

        return isset(static::$stateTransitions[$current]) && in_array($newState, static::$stateTransitions[$current], true);
    }

    /**
     * Transition the model to a new state if allowed.
     * Throws an exception if the transition is not allowed.
     */
    public function transitionTo($newState): void
    {
        if (! $this->canTransitionTo($newState)) {
            throw new \Exception("Invalid state transition from {$this->{$this->stateAttribute}} to {$newState}");
        }
        $this->{$this->stateAttribute} = $newState;
        $this->save();
    }
}
