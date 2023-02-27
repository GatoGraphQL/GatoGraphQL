<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\Services;

interface CacheControlForAccessControlManagerInterface
{
    /**
     * Not necessarily all groups must add @cacheControl(maxAge: 0).
     * Eg: AccessControlGroups::DISABLED does have CacheControl!
     *
     * @return string[]
     */
    public function getSupportingCacheControlAccessControlGroups(): array;
}
