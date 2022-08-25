<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\Misc;

use PoP\ComponentModel\HelperServices\RequestHelperService;
use PoP\Engine\Constants\Params;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class URLHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            RequestHelperService::HOOK_CURRENT_URL_REMOVE_PARAMS,
            $this->getParamsToRemoveFromURL(...)
        );
    }
    public function getParamsToRemoveFromURL($params)
    {
        $params[] = Params::HEADCOMPONENT;
        return $params;
    }
}
