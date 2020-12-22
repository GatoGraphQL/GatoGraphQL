<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'popLocationsGetKeysStoreAsArrayPosts');
function popLocationsGetKeysStoreAsArrayPosts($keys)
{
    $keys[] = GD_METAKEY_POST_LOCATIONS;
    return $keys;
}

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'popLocationsGetKeysStoreAsArrayProfiles');
function popLocationsGetKeysStoreAsArrayProfiles($keys)
{
    $keys[] = GD_METAKEY_PROFILE_LOCATIONS;
    return $keys;
}
