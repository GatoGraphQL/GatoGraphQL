<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ModuleFilters;

use PoP\ComponentModel\Facades\ModulePath\ModulePathHelpersFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;

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
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
        $this->getHooksAPI()->addAction(
            'ApplicationState:addVars',
            [$this, 'addVars'],
            10,
            1
        );
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        if (isset($vars['modulefilter']) && $vars['modulefilter'] == $this->modulePaths->getName()) {
            $vars['modulepaths'] = ModulePathUtils::getModulePaths();
        }
    }
    public function maybeAddComponent(array $components): array
    {
        $vars = ApplicationState::getVars();
        if (isset($vars['modulefilter']) && $vars['modulefilter'] == $this->modulePaths->getName()) {
            if ($modulepaths = $vars['modulepaths']) {
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
