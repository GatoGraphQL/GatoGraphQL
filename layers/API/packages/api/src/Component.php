<?php

declare(strict_types=1);

namespace PoP\API;

use PoP\Root\App;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\API\Configuration\Request;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\BasicService\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoP\Engine\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\AccessControl\Component::class,
            \PoP\CacheControl\Component::class,
        ];
    }

    protected function resolveEnabled(): bool
    {
        return !Environment::disableAPI();
    }

    /**
     * Set the default component configuration
     *
     * @param array<string, mixed> $componentClassConfiguration
     */
    public function customizeComponentClassConfiguration(
        array &$componentClassConfiguration
    ): void {
        // If passing ?use_namespace=1, set it on the configuration
        $useNamespacing = Request::mustNamespaceTypes();
        if ($useNamespacing !== null) {
            $componentClassConfiguration[\PoP\ComponentModel\Component::class][\PoP\ComponentModel\Environment::NAMESPACE_TYPES_AND_INTERFACES] = $useNamespacing;
        }
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        // Conditional packages
        if (class_exists(AccessControlComponent::class)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/AccessControl');
        }

        /** @var AccessControlComponentConfiguration */
        $componentConfiguration = App::getComponent(AccessControlComponent::class)->getConfiguration();
        if (
            class_exists(CacheControlComponent::class)
            && class_exists(AccessControlComponent::class)
            && $componentConfiguration->canSchemaBePrivate()
        ) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses) || in_array(\PoP\AccessControl\Component::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/CacheControl/ConditionalOnComponent/AccessControl/ConditionalOnContext/PrivateSchema'
            );
        }
    }
}
