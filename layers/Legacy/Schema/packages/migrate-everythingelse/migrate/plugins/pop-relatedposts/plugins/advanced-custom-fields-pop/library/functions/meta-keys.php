<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'gdAcfGetKeysStoreAsArrayPosts');
function gdAcfGetKeysStoreAsArrayPosts($keys)
{
    $keys[] = GD_METAKEY_POST_REFERENCES;
    return $keys;
}
