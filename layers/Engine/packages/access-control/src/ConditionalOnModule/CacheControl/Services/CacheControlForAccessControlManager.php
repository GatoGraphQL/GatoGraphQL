<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\Services;

class CacheControlForAccessControlManager implements CacheControlForAccessControlManagerInterface
{
    // /**
    //  * @var array<string,array<mixed[]>>
    //  */
    // protected array $fieldEntries = [];
    
    /**
     * Not necessarily all groups must add @cacheControl(maxAge: 0).
     * Eg: AccessControlGroups::DISABLED does have CacheControl!
     *
     * @return string[]
     */
    public function getSupportingCacheControlAccessControlGroups(): array
    {
        return [];
    }
}
