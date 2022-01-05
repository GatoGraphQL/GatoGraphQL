<?php

declare(strict_types=1);

namespace PoP\Root\Component;

abstract class AbstractComponentConfiguration implements ComponentConfigurationInterface
{
    final public function __construct(
        /** @var array<string,mixed> */
        protected array $configuration
    ) {
    }

    public function hasConfigurationValue(string $envVariable): bool
    {
        return array_key_exists($envVariable, $this->configuration);
    }

    public function getConfigurationValue(string $envVariable): mixed
    {
        return $this->configuration[$envVariable] ?? null;
    }

    protected function maybeInitializeConfigurationValue(
        string $envVariable,
        mixed $defaultValue,
        ?callable $callback = null
    ): void {
        // Initialized from configuration? Then use that one directly.
        if ($this->hasConfigurationValue($envVariable)) {
            return;
        }

        /**
         * Otherwise, initialize from environment.
         * First set the default value, for if there's no env var defined.
         */
        $this->configuration[$envVariable] = $defaultValue;

        /**
         * Get the value from the environment, converting it
         * to the appropriate type via a callback function.
         */
        $envValue = \getenv($envVariable);
        if ($envValue !== false) {
            // Modify the type of the variable, from string to bool/int/array
            $this->configuration[$envVariable] = $callback !== null ? $callback($envValue) : $envValue;
        }
    }
}
