<?php

declare(strict_types=1);

namespace PoP\RootWP;

use Brain\Cortex;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Environment;

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
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Root\Module::class,
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

    public function moduleLoaded(): void
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
