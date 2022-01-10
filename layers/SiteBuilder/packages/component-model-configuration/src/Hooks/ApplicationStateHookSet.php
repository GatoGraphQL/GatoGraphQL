<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;

class ApplicationStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
    }

    public function maybeAddComponent(array $components): array
    {
        $vars = ApplicationState::getVars();
        if ($target = $vars['target'] ?? null) {
            $components[] = $this->__('target:', 'component-model') . $target;
        }
        if ($format = $vars['format'] ?? null) {
            $components[] = $this->__('format:', 'component-model') . $format;
        }

        return $components;
    }
}
