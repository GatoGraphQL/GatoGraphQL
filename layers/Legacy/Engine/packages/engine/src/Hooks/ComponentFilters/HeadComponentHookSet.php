<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ComponentFilters;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\Modules\ComponentHelpersInterface;
use PoP\Engine\ComponentFilters\HeadComponent;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class HeadComponentHookSet extends AbstractHookSet
{
    private ?HeadComponent $headComponent = null;
    private ?ComponentHelpersInterface $componentHelpers = null;
    
    final public function setHeadComponent(HeadComponent $headComponent): void
    {
        $this->headComponent = $headComponent;
    }
    final protected function getHeadComponent(): HeadComponent
    {
        return $this->headComponent ??= $this->instanceManager->getInstance(HeadComponent::class);
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
