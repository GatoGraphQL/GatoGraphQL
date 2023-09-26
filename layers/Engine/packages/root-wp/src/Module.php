<?php

declare(strict_types=1);

namespace PoP\RootWP;

use Brain\Cortex;
use PoP\Root\App;
use PoP\Root\Environment;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
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
            RootModule::class,
        ];
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

        /** @var RootModuleConfiguration */
        $moduleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$moduleConfiguration->enablePassingRoutingStateViaRequest()) {
            return;
        }

        /**
         * Gato GraphQL plugin:
         * This code is never reached, because `enablePassingRoutingStateViaRequest`
         * is false. To optimize the plugin, library `brain/cortex` has been removed
         * from `composer.json` (that's why this class below is not found).
         *
         * Cortex is needed for the SiteBuilder or REST API module, as these
         * require a routing system.
         *
         * @see https://github.com/GatoGraphQL/SiteBuilder
         * @see https://github.com/GatoGraphQL/GatoGraphQL/tree/master/layers/API/packages/api-rest
         *
         * To use them, the following package must be added to the `require`
         * section in `composer.json`:
         *
         *   ```
         *   "require": {
         *     "brain/cortex": "1.0.0-alpha.9"
         *   }
         *   ```
         */
        Cortex::boot();
    }
}
