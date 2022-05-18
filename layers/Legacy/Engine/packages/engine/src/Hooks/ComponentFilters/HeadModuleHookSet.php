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
    private ?HeadModule $headComponent = null;
    private ?ModuleHelpersInterface $moduleHelpers = null;
    
    final public function setHeadModule(HeadModule $headComponent): void
    {
        $this->headComponent = $headComponent;
    }
    final protected function getHeadModule(): HeadModule
    {
        return $this->headComponent ??= $this->instanceManager->getInstance(HeadModule::class);
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
    
    public function maybeAddComponent(array $components): array
    {
        if (App::getState('componentFilter') === $this->headComponent->getName()) {
            if ($headComponent = App::getState('headComponent')) {
                $components[] = $this->getTranslationAPI()->__('head component:', 'engine') . $this->getModuleHelpers()->getModuleFullName($headComponent);
            }
        }
        return $components;
    }
}
