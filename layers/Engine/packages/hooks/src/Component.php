<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\Root\Component\AbstractComponent;
use PoP\Hooks\Container\CompilerPasses\InstantiateHookSetServiceCompilerPass;

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
            InstantiateHookSetServiceCompilerPass::class,
        ];
    }
}
