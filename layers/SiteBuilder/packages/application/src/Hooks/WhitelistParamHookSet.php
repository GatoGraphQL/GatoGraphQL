<?php

declare(strict_types=1);

namespace PoP\Application\Hooks;

use PoP\Root\App;
use PoP\Application\Constants\Response;
use PoP\ComponentModel\Constants\HookNames;
use PoP\Root\Hooks\AbstractHookSet;

class WhitelistParamHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::QUERYDATA_WHITELISTEDPARAMS,
            $this->getWhitelistedParams(...)
        );
    }

    /**
     * @return string[]
     */
    public function getWhitelistedParams(array $params): array
    {
        $params[] = Response::REDIRECT_TO;
        return $params;
    }
}
