<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl;

use PoP\CacheControl\Module as CacheControlModule;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\UserRoles\Module::class,
            \PoPCMSSchema\UserStateAccessControl\Module::class,
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

        if (class_exists(CacheControlModule::class)) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoP\CacheControl\Module::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/CacheControl'
            );
        }
    }
}
