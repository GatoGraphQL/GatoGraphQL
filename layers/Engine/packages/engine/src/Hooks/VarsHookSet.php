<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Engine\Component;
use PoP\Engine\ComponentConfiguration;
use PoP\BasicService\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        // Removing fields changes the configuration
        $components[] = $this->getTranslationAPI()->__('disable redundant root fields:', 'pop-engine') . ComponentConfiguration::disableRedundantRootTypeMutationFields();
        return $components;
    }
}
