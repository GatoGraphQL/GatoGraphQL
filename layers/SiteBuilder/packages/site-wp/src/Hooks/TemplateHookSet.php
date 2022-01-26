<?php

declare(strict_types=1);

namespace PoP\SiteWP\Hooks;

use PoP\EngineWP\Hooks\TemplateHookSet as UpstreamTemplateHookSet;

class TemplateHookSet extends UpstreamTemplateHookSet
{
    /**
     * Also handle when not doing JSON, to print HTML
     */
    protected function useTemplate(): bool
    {
        return !$this->getApplicationStateHelperService()->doingJSON();
    }
}
