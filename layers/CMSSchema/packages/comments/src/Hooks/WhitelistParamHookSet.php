<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\Hooks;

use PoP\ComponentModel\Constants\HookNames;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\Constants\Params;

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
     * @param string[] $params
     */
    public function getWhitelistedParams(array $params): array
    {
        // Used for the Comments to know what post to fetch comments from when filtering
        $params[] = Params::COMMENT_POST_ID;
        return $params;
    }
}
