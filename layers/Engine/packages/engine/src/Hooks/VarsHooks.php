<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Engine\ComponentConfiguration;

class VarsHooks extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        // Removing fields changes the configuration
        $components[] = $this->translationAPI->__('disable redundant root fields:', 'pop-engine') . ComponentConfiguration::disableRedundantRootTypeMutationFields();
        return $components;
    }
}
