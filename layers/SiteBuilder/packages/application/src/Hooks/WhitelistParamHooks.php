<?php

declare(strict_types=1);

namespace PoP\Application\Hooks;

use PoP\Application\Constants\Response;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModuleProcessors\Constants;

class WhitelistParamHooks extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS,
            array($this, 'getWhitelistedParams')
        );
    }

    public function getWhitelistedParams(array $params): array
    {
        $params[] = Response::REDIRECT_TO;
        return $params;
    }
}
