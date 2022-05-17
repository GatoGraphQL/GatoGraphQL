<?php

declare(strict_types=1);

namespace PoP\RootWP;

use Brain\Cortex;
use PoP\Root\Module\AbstractComponent;
use PoP\Root\Environment;

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
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        /**
         * Do not enable services when running PHPUnit tests
         * (the needed methods, such as `__`, will be satisfied
         * somewhere else)
         */
        if (Environment::isApplicationEnvironmentDevPHPUnit()) {
            return;
        };

        $this->initServices(dirname(__DIR__), '', 'hybrid-services.yaml');
        $this->initServices(dirname(__DIR__));
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        /**
         * Do not enable services when running PHPUnit tests
         * (the needed methods, such as `__`, will be satisfied
         * somewhere else)
         */
        if (Environment::isApplicationEnvironmentDevPHPUnit()) {
            return;
        };
        $this->initSystemServices(dirname(__DIR__), '', 'hybrid-services.yaml');
    }

    public function componentLoaded(): void
    {
        /**
         * Do not enable services when running PHPUnit tests
         * (the needed methods, such as `__`, will be satisfied
         * somewhere else)
         */
        if (Environment::isApplicationEnvironmentDevPHPUnit()) {
            return;
        };
        Cortex::boot();
    }
}
