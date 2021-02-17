<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\ModuleRouting\Container\CompilerPasses\RegisterRouteModuleProcessorCompilerPass;
use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\Root\Component::class,
        ];
    }

    /**
     * Get all the compiler pass classes required to register on the container
     *
     * @return string[]
     */
    public static function getContainerCompilerPassClasses(): array
    {
        return [
            RegisterRouteModuleProcessorCompilerPass::class,
        ];
    }
}
