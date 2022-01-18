<?php

declare(strict_types=1);

namespace PoP\Engine\State;

use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Engine\Configuration\Request;
use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Engine\ModuleFilters\MainContentModule;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\App;
use PoP\Root\Component as RootComponent;
use PoP\Root\ComponentConfiguration as RootComponentConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?HeadModule $headModule = null;
    private ?ModulePaths $modulePaths = null;
    private ?MainContentModule $mainContentModule = null;
    private ?RouteModuleProcessorManagerInterface $routeModuleProcessorManager = null;
    private ?ModulePathHelpersInterface $modulePathHelpers = null;

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
    final public function setRouteModuleProcessorManager(RouteModuleProcessorManagerInterface $routeModuleProcessorManager): void
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }
    final protected function getRouteModuleProcessorManager(): RouteModuleProcessorManagerInterface
    {
        return $this->routeModuleProcessorManager ??= $this->instanceManager->getInstance(RouteModuleProcessorManagerInterface::class);
    }
    final public function setModulePathHelpers(ModulePathHelpersInterface $modulePathHelpers): void
    {
        $this->modulePathHelpers = $modulePathHelpers;
    }
    final protected function getModulePathHelpers(): ModulePathHelpersInterface
    {
        return $this->modulePathHelpers ??= $this->instanceManager->getInstance(ModulePathHelpersInterface::class);
    }

    public function augment(array &$state): void
    {
        if ($state['modulefilter'] === null) {
            return;
        }

        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootComponent::class)->getConfiguration();
        $enablePassingStateViaRequest = $rootComponentConfiguration->enablePassingStateViaRequest();

        if ($state['modulefilter'] === $this->headModule->getName()) {
            if ($enablePassingStateViaRequest) {
                if ($headmodule = Request::getHeadModule()) {
                    $state['headmodule'] = ModuleUtils::getModuleFromOutputName($headmodule);
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
            $state['maincontentmodule'] = $this->getRouteModuleProcessorManager()->getRouteModuleByMostAllmatchingVarsProperties(\POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
        }
    }
}
