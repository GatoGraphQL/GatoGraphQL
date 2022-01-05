<?php

declare(strict_types=1);

namespace PoP\BasicService\Component;

use PoP\BasicService\Component\ComponentConfigurationHelpers;
use PoP\Hooks\Facades\SystemHooksAPIFacade;
use PoP\Root\Component\AbstractComponentConfiguration as UpstreamAbstractComponentConfiguration;
use PoP\Root\Helpers\ClassHelpers;

/**
 * Initialize component
 */
abstract class AbstractComponentConfiguration extends UpstreamAbstractComponentConfiguration implements ComponentConfigurationInterface
{
    protected function maybeInitializeConfigurationValue(
        string $envVariable,
        mixed $defaultValue = null,
        ?callable $callback = null
    ): void {
        // Initialized from configuration? Then use that one directly.
        if ($this->hasConfigurationValue($envVariable)) {
            return;
        }
        
        /**
         * Otherwise, initialize from environment or hook.
         * First set the default value, for if there's no env var or hook.
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

        /**
         * Get the value via a hook.
         *
         * Important: it must use the Hooks service from the System Container,
         * and not the (Application) Container, because ComponentConfiguration::foo()
         * may be accessed when initializing (Application) container services
         * in `Component.initialize()`, so it must already be available by then
         */
        $hooksAPI = SystemHooksAPIFacade::getInstance();
        $class = $this->getComponentClass();
        $hookName = ComponentConfigurationHelpers::getHookName(
            $class,
            $envVariable
        );
        $this->configuration[$envVariable] = $hooksAPI->applyFilters(
            $hookName,
            $this->configuration[$envVariable],
            $class,
            $envVariable
        );
    }

    /**
     * Package's Component class, of type ComponentInterface.
     * By standard, it is "NamespaceOwner\Project\Component::class"
     */
    protected function getComponentClass(): string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        return $classNamespace . '\\Component';
    }
}
