<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ComponentFilters;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;
use PoP\Engine\ComponentFilters\HeadModule;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class HeadModuleHookSet extends AbstractHookSet
{
    private ?HeadModule $headModule = null;
    private ?ModuleHelpersInterface $moduleHelpers = null;
    
    final public function setHeadModule(HeadModule $headModule): void
    {
        $this->headModule = $headModule;
    }
    final protected function getHeadModule(): HeadModule
    {
        return $this->headModule ??= $this->instanceManager->getInstance(HeadModule::class);
    }
    final public function setModuleHelpers(ModuleHelpersInterface $moduleHelpers): void
    {
        $this->moduleHelpers = $moduleHelpers;
    }
    final protected function getModuleHelpers(): ModuleHelpersInterface
    {
        return $this->moduleHelpers ??= $this->instanceManager->getInstance(ModuleHelpersInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            $this->maybeAddComponent(...)
        );
    }
    
    public function maybeAddComponent($components)
    {
        if (App::getState('componentFilter') === $this->headModule->getName()) {
            if ($headmodule = App::getState('headmodule')) {
                $components[] = $this->getTranslationAPI()->__('head module:', 'engine') . $this->getModuleHelpers()->getModuleFullName($headmodule);
            }
        }

        return $components;
    }
}
