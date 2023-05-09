<?php

declare(strict_types=1);

namespace PoPAPI\API;

use PoPAPI\API\Configuration\Request;
use PoP\AccessControl\Module as AccessControlModule;
use PoP\AccessControl\ModuleConfiguration as AccessControlModuleConfiguration;
use PoP\CacheControl\Module as CacheControlModule;
use PoP\Root\App;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Engine\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            AccessControlModule::class,
            CacheControlModule::class,
        ];
    }

    protected function resolveEnabled(): bool
    {
        return !Environment::disableAPI();
    }

    /**
     * Set the default component configuration
     *
     * @param array<string,mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void {
        parent::customizeModuleClassConfiguration($moduleClassConfiguration);

        // If passing ?use_namespace=1, set it on the configuration
        $useNamespacing = Request::mustNamespaceTypes();
        if ($useNamespacing !== null) {
            $moduleClassConfiguration[\PoP\ComponentModel\Module::class][\PoP\ComponentModel\Environment::NAMESPACE_TYPES_AND_INTERFACES] = $useNamespacing;
        }
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        // Conditional packages
        try {
            if (class_exists(AccessControlModule::class) && App::getModule(AccessControlModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/AccessControl');

                /** @var AccessControlModuleConfiguration */
                $moduleConfiguration = App::getModule(AccessControlModule::class)->getConfiguration();
                try {
                    if (
                        class_exists(CacheControlModule::class)
                        && App::getModule(CacheControlModule::class)->isEnabled()
                        && $moduleConfiguration->canSchemaBePrivate()
                    ) {
                        $this->initSchemaServices(
                            dirname(__DIR__),
                            $skipSchema || in_array(CacheControlModule::class, $skipSchemaModuleClasses) || in_array(AccessControlModule::class, $skipSchemaModuleClasses),
                            '/ConditionalOnModule/CacheControl/ConditionalOnModule/AccessControl/ConditionalOnContext/PrivateSchema'
                        );
                    }
                } catch (ComponentNotExistsException) {
                }
            }
        } catch (ComponentNotExistsException) {
        }
    }
}
