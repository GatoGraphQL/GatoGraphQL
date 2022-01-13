<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;

class ApplicationStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
    }

    public function maybeAddComponent(array $components): array
    {
        if ($target = App::getState('target')) {
            $components[] = $this->__('target:', 'component-model') . $target;
        }
        if ($format = App::getState('format')) {
            $components[] = $this->__('format:', 'component-model') . $format;
        }

        return $components;
    }
}
