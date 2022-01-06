<?php

declare(strict_types=1);

namespace PoP\BasicService\Component;

use PoP\Hooks\Facades\SystemHooksAPIFacade;
use PoP\Root\Component\AbstractComponentConfiguration as UpstreamAbstractComponentConfiguration;
use PoP\Root\Helpers\ClassHelpers;

/**
 * Initialize component
 */
abstract class AbstractComponentConfiguration extends UpstreamAbstractComponentConfiguration implements ComponentConfigurationInterface
{
    protected function retrieveConfigurationValueOrUseDefault(
        string $envVariable,
        mixed $defaultValue,
        ?callable $callback = null
    ): mixed {
        // Initialized from configuration? Then use that one directly.
        if ($this->hasConfigurationValue($envVariable)) {
            return $this->getConfigurationValue($envVariable);
        }

        // Initialize via environment
        $upstreamValue = parent::retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);

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
            $upstreamValue,
            $class,
            $envVariable
        );

        return $this->configuration[$envVariable];
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
