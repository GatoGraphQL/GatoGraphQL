<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\Misc;

use PoP\Engine\Constants\Params;
use PoP\Root\Hooks\AbstractHookSet;

class URLHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'RequestUtils:current_url:remove_params',
            [$this, 'getParamsToRemoveFromURL']
        );
    }
    public function getParamsToRemoveFromURL($params)
    {
        $params[] = Params::HEADMODULE;
        return $params;
    }
}
