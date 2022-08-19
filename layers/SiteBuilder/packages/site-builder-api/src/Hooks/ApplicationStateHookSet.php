<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\Hooks\AbstractHookSet;

class ApplicationStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_ELEMENTSFROMVARS_RESULT,
            $this->maybeAddElement(...)
        );
    }

    /**
     * @param string[] $elements
     * @return string[]
     */
    public function maybeAddElement(array $elements): array
    {
        if ($stratum = App::getState('stratum')) {
            $elements[] = $this->__('stratum:', 'component-model') . $stratum;
        }

        return $elements;
    }
}
