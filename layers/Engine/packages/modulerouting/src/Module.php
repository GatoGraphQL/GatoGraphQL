<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\Root\Module\AbstractComponent;

/**
 * Initialize component
 */
class Module extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoP\Root\Module::class,
        ];
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__));
    }
}
