<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ComponentFilters;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\Modules\ComponentHelpersInterface;
use PoP\Engine\ComponentFilters\HeadModule;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class HeadModuleHookSet extends AbstractHookSet
{
    private ?HeadModule $headComponent = null;
    private ?ComponentHelpersInterface $componentHelpers = null;
    
    final public function setHeadModule(HeadModule $headComponent): void
    {
        $this->headComponent = $headComponent;
    }
    final protected function getHeadModule(): HeadModule
    {
        return $this->headComponent ??= $this->instanceManager->getInstance(HeadModule::class);
    }
    final public function setComponentHelpers(ComponentHelpersInterface $componentHelpers): void
    {
        $this->componentHelpers = $componentHelpers;
    }
    final protected function getComponentHelpers(): ComponentHelpersInterface
    {
        return $this->componentHelpers ??= $this->instanceManager->getInstance(ComponentHelpersInterface::class);
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
                $components[] = $this->getTranslationAPI()->__('head component:', 'engine') . $this->getComponentHelpers()->getComponentFullName($headComponent);
            }
        }
        return $components;
    }
}
