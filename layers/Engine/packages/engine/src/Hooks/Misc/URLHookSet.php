<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\Misc;

use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Hooks\AbstractHookSet;

class URLHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            'RequestUtils:current_url:remove_params',
            [$this, 'getParamsToRemoveFromURL']
        );
    }
    public function getParamsToRemoveFromURL($params)
    {
        $params[] = HeadModule::URLPARAM_HEADMODULE;
        return $params;
    }
}
