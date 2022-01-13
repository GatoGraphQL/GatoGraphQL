<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsSingle', 'userstanceAcfGetKeysStoreAsSingleCustom');
function userstanceAcfGetKeysStoreAsSingleCustom($keys)
{
    $keys[] = GD_URE_METAKEY_POST_AUTHORROLE;
    return $keys;
}
