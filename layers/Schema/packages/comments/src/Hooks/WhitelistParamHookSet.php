<?php

declare(strict_types=1);

namespace PoPSchema\Comments\Hooks;

use PoPSchema\Comments\Constants\Params;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModuleProcessors\Constants;

class WhitelistParamHookSet extends AbstractHookSet
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
        // Used for the Comments to know what post to fetch comments from when filtering
        $params[] = Params::COMMENT_POST_ID;
        return $params;
    }
}
