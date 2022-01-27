<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Engine\Component;
use PoP\Engine\ComponentConfiguration;
use PoP\Root\Hooks\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromAppState')
        );
    }

    public function getModelInstanceComponentsFromAppState($components)
    {
        // Removing fields changes the configuration
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $components[] = $this->__('disable redundant root fields:', 'pop-engine') . $componentConfiguration->disableRedundantRootTypeMutationFields();
        return $components;
    }
}
