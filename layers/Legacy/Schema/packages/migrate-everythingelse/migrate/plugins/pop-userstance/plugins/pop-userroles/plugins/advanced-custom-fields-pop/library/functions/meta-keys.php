<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsSingle', 'userstanceAcfGetKeysStoreAsSingleCustom');
function userstanceAcfGetKeysStoreAsSingleCustom($keys)
{
    $keys[] = GD_URE_METAKEY_POST_AUTHORROLE;
    return $keys;
}
