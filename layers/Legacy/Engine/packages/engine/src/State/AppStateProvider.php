<?php

declare(strict_types=1);

namespace PoP\Engine\State;

use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathUtils;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Engine\ModuleFilters\MainContentModule;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?HeadModule $headModule = null;
    private ?ModulePaths $modulePaths = null;
    private ?MainContentModule $mainContentModule = null;
    
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

    public function initialize(array &$state): void
    {
        if (isset($state['modulefilter']) && $state['modulefilter'] === $this->headModule->getName()) {
            if ($headmodule = $_REQUEST[HeadModule::URLPARAM_HEADMODULE] ?? null) {
                $state['headmodule'] = ModuleUtils::getModuleFromOutputName($headmodule);
            }
        }
        if (isset($state['modulefilter']) && $state['modulefilter'] === $this->modulePaths->getName()) {
            $state['modulepaths'] = ModulePathUtils::getModulePaths();
        }
    }

    public function augment(array &$state): void
    {
        // Function `getRouteModuleByMostAllmatchingVarsProperties` actually needs to access all values in $state
        // Hence, calculate only at the very end
        // If filtering module by "maincontent", then calculate which is the main content module
        if (isset($state['modulefilter']) && $state['modulefilter'] === $this->mainContentModule->getName()) {
            $state['maincontentmodule'] = RouteModuleProcessorManagerFacade::getInstance()->getRouteModuleByMostAllmatchingVarsProperties(\POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
        }
    }
}
