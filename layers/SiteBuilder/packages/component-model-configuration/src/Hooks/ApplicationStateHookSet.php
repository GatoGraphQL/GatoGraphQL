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
        App::addFilter(
            ModelInstance::HOOK_ELEMENTSFROMVARS_RESULT,
            $this->maybeAddComponent(...)
        );
    }

    public function maybeAddComponent(array $elements): array
    {
        if ($target = App::getState('target')) {
            $elements[] = $this->__('target:', 'component-model') . $target;
        }
        if ($format = App::getState('format')) {
            $elements[] = $this->__('format:', 'component-model') . $format;
        }

        return $elements;
    }
}
