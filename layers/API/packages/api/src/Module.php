<?php

declare(strict_types=1);

namespace PoPAPI\API;

use PoP\Root\App;
use PoP\AccessControl\Module as AccessControlModule;
use PoP\AccessControl\ModuleConfiguration as AccessControlModuleConfiguration;
use PoPAPI\API\Configuration\Request;
use PoP\CacheControl\Module as CacheControlModule;
use PoP\Root\Module\AbstractModule;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoP\Engine\Module::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\AccessControl\Module::class,
            \PoP\CacheControl\Module::class,
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
            $componentClassConfiguration[\PoP\ComponentModel\Module::class][\PoP\ComponentModel\Environment::NAMESPACE_TYPES_AND_INTERFACES] = $useNamespacing;
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
        if (class_exists(AccessControlModule::class)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnModule/AccessControl');
        }

        /** @var AccessControlModuleConfiguration */
        $moduleConfiguration = App::getModule(AccessControlModule::class)->getConfiguration();
        if (
            class_exists(CacheControlModule::class)
            && class_exists(AccessControlModule::class)
            && $moduleConfiguration->canSchemaBePrivate()
        ) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoP\CacheControl\Module::class, $skipSchemaComponentClasses) || in_array(\PoP\AccessControl\Module::class, $skipSchemaComponentClasses),
                '/ConditionalOnModule/CacheControl/ConditionalOnModule/AccessControl/ConditionalOnContext/PrivateSchema'
            );
        }
    }
}
