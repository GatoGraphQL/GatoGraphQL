<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ModuleFilters;

use PoP\Root\App;
use PoP\ComponentModel\Facades\ModulePath\ModulePathHelpersFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\Root\Hooks\AbstractHookSet;

class ModulePathsHookSet extends AbstractHookSet
{
    private ?ModulePaths $modulePaths = null;
    
    final public function setModulePaths(ModulePaths $modulePaths): void
    {
        $this->modulePaths = $modulePaths;
    }
    final protected function getModulePaths(): ModulePaths
    {
        return $this->modulePaths ??= $this->instanceManager->getInstance(ModulePaths::class);
    }

    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
    }
    
    public function maybeAddComponent(array $components): array
    {
        if (App::getState('modulefilter') === $this->modulePaths->getName()) {
            if ($modulepaths = App::getState('modulepaths')) {
                $modulePathHelpers = ModulePathHelpersFacade::getInstance();
                $paths = array_map(
                    fn ($modulepath) => $modulePathHelpers->stringifyModulePath($modulepath),
                    $modulepaths
                );
                $components[] = $this->getTranslationAPI()->__('module paths:', 'engine') . implode(',', $paths);
            }
        }

        return $components;
    }
}
