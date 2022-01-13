<?php

declare(strict_types=1);

namespace PoP\Application\Hooks;

use PoP\Application\Constants\Response;
use PoP\ComponentModel\ModuleProcessors\Constants;
use PoP\Root\Hooks\AbstractHookSet;

class WhitelistParamHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
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
