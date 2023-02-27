<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\Services;

use PoP\AccessControl\ConditionalOnModule\CacheControl\Constants\HookNames;
use PoP\ComponentModel\App;
use PoP\Root\Services\BasicServiceTrait;

class CacheControlForAccessControlManager implements CacheControlForAccessControlManagerInterface
{
    use BasicServiceTrait;

    /**
     * @var string[]
     */
    protected ?array $supportingCacheControlAccessControlGroups = null;

    /**
     * Not necessarily all groups must add @cacheControl(maxAge: 0).
     * Eg: AccessControlGroups::DISABLED does have CacheControl!
     *
     * @return string[]
     */
    public function getSupportingCacheControlAccessControlGroups(): array
    {
        if ($this->supportingCacheControlAccessControlGroups === null) {
            $this->supportingCacheControlAccessControlGroups = App::applyFilters(
                HookNames::SUPPORTING_CACHE_CONTROL_ACCESS_CONTROL_GROUPS,
                []
            );
        }
        return $this->supportingCacheControlAccessControlGroups;
    }
}
