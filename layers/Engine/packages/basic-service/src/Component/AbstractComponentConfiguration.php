<?php

declare(strict_types=1);

namespace PoP\BasicService\Component;

use PoP\BasicService\Component\ComponentConfigurationHelpers;
use PoP\Hooks\Facades\SystemHooksAPIFacade;
use PoP\Root\Component\AbstractComponentConfiguration as UpstreamAbstractComponentConfiguration;

/**
 * Initialize component
 */
abstract class AbstractComponentConfiguration extends UpstreamAbstractComponentConfiguration implements ComponentConfigurationInterface
{
    protected function maybeInitializeConfigurationValue(
        string $envVariable,
        mixed &$selfProperty,
        mixed $defaultValue = null,
        ?callable $callback = null
    ): void {
        // Only set default value if passing the param,
        // to avoid overriding a value already set in the param definition
        if ($defaultValue !== null) {
            $selfProperty = $defaultValue;
        }
        // Initialize from configuration, environment or hook
        if ($this->hasConfigurationValue($envVariable)) {
            // Priority: option has been set in the $configuration
            $selfProperty = $this->getConfigurationValue($envVariable);
        } else {
            // Get the value from the environment function
            $envValue = \getenv($envVariable);
            if ($envValue !== false) {
                // Modify the type of the variable, from string to bool/int/array
                $selfProperty = $callback !== null ? $callback($envValue) : $envValue;
            }
            /**
             * Important: it must use the Hooks service from the System Container,
             * and not the (Application) Container, because ComponentConfiguration::foo()
             * may be accessed when initializing (Application) container services
             * in `Component.initialize()`, so it must already be available by then
             */
            $hooksAPI = SystemHooksAPIFacade::getInstance();
            $class = \get_called_class();
            $hookName = ComponentConfigurationHelpers::getHookName(
                $class,
                $envVariable
            );
            $selfProperty = $hooksAPI->applyFilters(
                $hookName,
                $selfProperty,
                $class,
                $envVariable
            );
        }
    }
}
