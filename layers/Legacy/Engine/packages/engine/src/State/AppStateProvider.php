<?php

declare(strict_types=1);

namespace PoP\Engine\State;

use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;
use PoP\Engine\Configuration\Request;
use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Engine\ModuleFilters\MainContentModule;
use PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?HeadModule $headModule = null;
    private ?ModulePaths $modulePaths = null;
    private ?MainContentModule $mainContentModule = null;
    private ?ComponentRoutingProcessorManagerInterface $routeModuleProcessorManager = null;
    private ?ModulePathHelpersInterface $modulePathHelpers = null;
    private ?ModuleHelpersInterface $moduleHelpers = null;

    final public function setHeadModule(HeadModule $headModule): void
    {
        $this->headModule = $headModule;
    }
    final protected function getHeadModule(): HeadModule
    {
        return $this->headModule ??= $this->instanceManager->getInstance(HeadModule::class);
    }    
    final public function setModulePaths(ModulePaths $modulePaths): void
    {
        $this->modulePaths = $modulePaths;
    }
    final protected function getModulePaths(): ModulePaths
    {
        return $this->modulePaths ??= $this->instanceManager->getInstance(ModulePaths::class);
    }
    final public function setMainContentModule(MainContentModule $mainContentModule): void
    {
        $this->mainContentModule = $mainContentModule;
    }
    final protected function getMainContentModule(): MainContentModule
    {
        return $this->mainContentModule ??= $this->instanceManager->getInstance(MainContentModule::class);
    }
    final public function setComponentRoutingProcessorManager(ComponentRoutingProcessorManagerInterface $routeModuleProcessorManager): void
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }
    final protected function getComponentRoutingProcessorManager(): ComponentRoutingProcessorManagerInterface
    {
        return $this->routeModuleProcessorManager ??= $this->instanceManager->getInstance(ComponentRoutingProcessorManagerInterface::class);
    }
    final public function setModulePathHelpers(ModulePathHelpersInterface $modulePathHelpers): void
    {
        $this->modulePathHelpers = $modulePathHelpers;
    }
    final protected function getModulePathHelpers(): ModulePathHelpersInterface
    {
        return $this->modulePathHelpers ??= $this->instanceManager->getInstance(ModulePathHelpersInterface::class);
    }
    final public function setModuleHelpers(ModuleHelpersInterface $moduleHelpers): void
    {
        $this->moduleHelpers = $moduleHelpers;
    }
    final protected function getModuleHelpers(): ModuleHelpersInterface
    {
        return $this->moduleHelpers ??= $this->instanceManager->getInstance(ModuleHelpersInterface::class);
    }

    public function augment(array &$state): void
    {
        if ($state['modulefilter'] === null) {
            return;
        }

        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        $enablePassingStateViaRequest = $rootModuleConfiguration->enablePassingStateViaRequest();

        if ($state['modulefilter'] === $this->headModule->getName()) {
            if ($enablePassingStateViaRequest) {
                if ($headmodule = Request::getHeadModule()) {
                    $state['headmodule'] = $this->getModuleHelpers()->getModuleFromOutputName($headmodule);
                }
            }
        }
        if ($state['modulefilter'] === $this->modulePaths->getName()) {
            if ($enablePassingStateViaRequest) {
                $state['modulepaths'] = $this->getModulePathHelpers()->getModulePaths();
            }
        }
        // Function `getRouteModuleByMostAllmatchingVarsProperties` actually needs to access all values in $state
        // Hence, calculate only at the very end
        // If filtering module by "maincontent", then calculate which is the main content module
        if ($state['modulefilter'] === $this->mainContentModule->getName()) {
            $state['maincontentmodule'] = $this->getComponentRoutingProcessorManager()->getRouteModuleByMostAllmatchingVarsProperties(\POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
        }
    }
}
