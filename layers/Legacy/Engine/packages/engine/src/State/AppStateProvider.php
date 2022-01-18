<?php

declare(strict_types=1);

namespace PoP\Engine\State;

use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathUtils;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Engine\Constants\Params;
use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Engine\ModuleFilters\MainContentModule;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?HeadModule $headModule = null;
    private ?ModulePaths $modulePaths = null;
    private ?MainContentModule $mainContentModule = null;
    private ?RouteModuleProcessorManagerInterface $routeModuleProcessorManager = null;

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

    public function augment(array &$state): void
    {
        if ($state['modulefilter'] === $this->headModule->getName()) {
            if ($headmodule = $_REQUEST[Params::HEADMODULE] ?? null) {
                $state['headmodule'] = ModuleUtils::getModuleFromOutputName($headmodule);
            }
        }
        if ($state['modulefilter'] === $this->modulePaths->getName()) {
            $state['modulepaths'] = ModulePathUtils::getModulePaths();
        }
        // Function `getRouteModuleByMostAllmatchingVarsProperties` actually needs to access all values in $state
        // Hence, calculate only at the very end
        // If filtering module by "maincontent", then calculate which is the main content module
        if ($state['modulefilter'] === $this->mainContentModule->getName()) {
            $state['maincontentmodule'] = $this->getRouteModuleProcessorManager()->getRouteModuleByMostAllmatchingVarsProperties(\POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
        }
    }
}
