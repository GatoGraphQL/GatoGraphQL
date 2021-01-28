<?php

declare(strict_types=1);

namespace PoP\Application\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModuleProcessors\Constants;

class WhitelistParamHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(
            Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS,
            array($this, 'getWhitelistedParams')
        );
    }

    public function getWhitelistedParams(array $params): array
    {
        $params[] = \PoP\Application\Constants\Response::REDIRECT_TO;
        return $params;
    }
}
