<?php

declare(strict_types=1);

namespace PoP\Engine\State;

use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathUtils;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?HeadModule $headModule = null;
    private ?ModulePaths $modulePaths = null;
    
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

    public function initialize(array &$state): void
    {
        if (isset($state['modulefilter']) && $state['modulefilter'] === $this->headModule->getName()) {
            if ($headmodule = $_REQUEST[HeadModule::URLPARAM_HEADMODULE] ?? null) {
                $state['headmodule'] = ModuleUtils::getModuleFromOutputName($headmodule);
            }
        }
        if (isset($vars['modulefilter']) && $vars['modulefilter'] === $this->modulePaths->getName()) {
            $vars['modulepaths'] = ModulePathUtils::getModulePaths();
        }
    }
}
