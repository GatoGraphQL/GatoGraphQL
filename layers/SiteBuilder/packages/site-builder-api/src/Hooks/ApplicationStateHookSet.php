<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;

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
        if ($stratum = $vars['stratum'] ?? null) {
            $components[] = $this->__('stratum:', 'component-model') . $stratum;
        }

        return $components;
    }
}
