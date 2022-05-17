<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Engine\Module;
use PoP\Engine\ModuleConfiguration;
use PoP\Root\Hooks\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            $this->getModelInstanceComponentsFromAppState(...)
        );
    }

    public function getModelInstanceComponentsFromAppState($components)
    {
        // Removing fields changes the configuration
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $components[] = $this->__('disable redundant root fields:', 'pop-engine') . $moduleConfiguration->disableRedundantRootTypeMutationFields();
        return $components;
    }
}
