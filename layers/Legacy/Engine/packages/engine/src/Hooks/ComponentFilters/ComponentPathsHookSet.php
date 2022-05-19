<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ComponentFilters;

use PoP\Root\App;
use PoP\ComponentModel\Facades\ComponentPath\ComponentPathHelpersFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\ComponentFilters\ComponentPaths;
use PoP\Root\Hooks\AbstractHookSet;

class ComponentPathsHookSet extends AbstractHookSet
{
    private ?ComponentPaths $componentPaths = null;
    
    final public function setComponentPaths(ComponentPaths $componentPaths): void
    {
        $this->componentPaths = $componentPaths;
    }
    final protected function getComponentPaths(): ComponentPaths
    {
        return $this->componentPaths ??= $this->instanceManager->getInstance(ComponentPaths::class);
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
        if (App::getState('componentFilter') === $this->componentPaths->getName()) {
            if ($componentPaths = App::getState('componentPaths')) {
                $componentPathHelpers = ComponentPathHelpersFacade::getInstance();
                $paths = array_map(
                    fn ($componentPath) => $componentPathHelpers->stringifyComponentPath($componentPath),
                    $componentPaths
                );
                $components[] = $this->getTranslationAPI()->__('component paths:', 'engine') . implode(',', $paths);
            }
        }

        return $components;
    }
}
