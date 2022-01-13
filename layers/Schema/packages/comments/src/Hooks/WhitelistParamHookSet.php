<?php

declare(strict_types=1);

namespace PoPSchema\Comments\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModuleProcessors\Constants;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\Comments\Constants\Params;

class WhitelistParamHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::getHookManager()->addFilter(
            Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS,
            array($this, 'getWhitelistedParams')
        );
    }

    public function getWhitelistedParams(array $params): array
    {
        // Used for the Comments to know what post to fetch comments from when filtering
        $params[] = Params::COMMENT_POST_ID;
        return $params;
    }
}
