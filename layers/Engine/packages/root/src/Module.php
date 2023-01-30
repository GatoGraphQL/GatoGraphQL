<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Container\HybridCompilerPasses\AutomaticallyInstantiatedServiceCompilerPass;
use PoP\Root\Container\ServiceInstantiatorInterface;
use PoP\Root\Container\SystemCompilerPasses\RegisterSystemCompilerPassServiceCompilerPass;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ApplicationEvents;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [];
    }

    /**
     * Compiler Passes for the System Container
     *
     * @return array<class-string<CompilerPassInterface>>
     */
    public function getSystemContainerCompilerPassClasses(): array
    {
        return [
            RegisterSystemCompilerPassServiceCompilerPass::class,
            // Needed to initialize ModuleListTableAction
            AutomaticallyInstantiatedServiceCompilerPass::class,
        ];
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__), '', 'hybrid-services.yaml');
        $this->initSystemServices(dirname(__DIR__));
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
        $this->initServices(dirname(__DIR__), '', 'hybrid-services.yaml');
        $this->initServices(dirname(__DIR__));
    }

    /**
     * Function called by the Bootloader after initializing the SystemContainer
     */
    public function bootSystem(): void
    {
        // Initialize container services through AutomaticallyInstantiatedServiceCompilerPass
        /**
         * @var ServiceInstantiatorInterface
         */
        $serviceInstantiator = App::getSystemContainer()->get(ServiceInstantiatorInterface::class);
        $serviceInstantiator->initializeServices();
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     */
    public function moduleLoaded(): void
    {
        // Initialize container services through AutomaticallyInstantiatedServiceCompilerPass
        /**
         * @var ServiceInstantiatorInterface
         */
        $serviceInstantiator = App::getContainer()->get(ServiceInstantiatorInterface::class);
        $serviceInstantiator->initializeServices(ApplicationEvents::MODULE_LOADED);
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     */
    public function preBoot(): void
    {
        // Initialize container services through AutomaticallyInstantiatedServiceCompilerPass
        /**
         * @var ServiceInstantiatorInterface
         */
        $serviceInstantiator = App::getContainer()->get(ServiceInstantiatorInterface::class);
        $serviceInstantiator->initializeServices(ApplicationEvents::PRE_BOOT);
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     */
    public function boot(): void
    {
        // Initialize container services through AutomaticallyInstantiatedServiceCompilerPass
        /**
         * @var ServiceInstantiatorInterface
         */
        $serviceInstantiator = App::getContainer()->get(ServiceInstantiatorInterface::class);
        $serviceInstantiator->initializeServices(ApplicationEvents::BOOT);
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     */
    public function afterBoot(): void
    {
        // Initialize container services through AutomaticallyInstantiatedServiceCompilerPass
        /**
         * @var ServiceInstantiatorInterface
         */
        $serviceInstantiator = App::getContainer()->get(ServiceInstantiatorInterface::class);
        $serviceInstantiator->initializeServices(ApplicationEvents::AFTER_BOOT);
    }
}
