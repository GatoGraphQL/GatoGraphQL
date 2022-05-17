<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl;

use PoP\CacheControl\Module as CacheControlModule;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\UserState\Module::class,
            \PoP\AccessControl\Module::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            \PoP\CacheControl\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        // Init conditional on API package being installed
        if (class_exists(CacheControlModule::class)) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoP\CacheControl\Module::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/CacheControl'
            );
        }
    }
}
