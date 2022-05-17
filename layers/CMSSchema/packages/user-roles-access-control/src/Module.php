<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl;

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
            \PoPCMSSchema\UserRoles\Module::class,
            \PoPCMSSchema\UserStateAccessControl\Module::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\CacheControl\Module::class,
        ];
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
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists(CacheControlModule::class)) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoP\CacheControl\Module::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/CacheControl'
            );
        }
    }
}
