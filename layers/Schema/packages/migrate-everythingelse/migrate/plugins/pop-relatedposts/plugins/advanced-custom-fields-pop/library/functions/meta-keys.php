<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'gdAcfGetKeysStoreAsArrayPosts');
function gdAcfGetKeysStoreAsArrayPosts($keys)
{
    $keys[] = GD_METAKEY_POST_REFERENCES;
    return $keys;
}
