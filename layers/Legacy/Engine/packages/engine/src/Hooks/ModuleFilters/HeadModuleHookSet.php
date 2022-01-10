<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ModuleFilters;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\ModuleFilters\HeadModule;
use PoP\BasicService\AbstractHookSet;

class HeadModuleHookSet extends AbstractHookSet
{
    private ?HeadModule $headModule = null;
    
    final public function setHeadModule(HeadModule $headModule): void
    {
        $this->headModule = $headModule;
    }
    final protected function getHeadModule(): HeadModule
    {
        return $this->headModule ??= $this->instanceManager->getInstance(HeadModule::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
    }
    
    public function maybeAddComponent($components)
    {
        $vars = ApplicationState::getVars();
        if (isset($vars['modulefilter']) && $vars['modulefilter'] === $this->headModule->getName()) {
            if ($headmodule = $vars['headmodule']) {
                $components[] = $this->getTranslationAPI()->__('head module:', 'engine') . ModuleUtils::getModuleFullName($headmodule);
            }
        }

        return $components;
    }
}
