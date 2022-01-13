<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'gdAcfGetKeysStoreAsArrayComments');
function gdAcfGetKeysStoreAsArrayComments($keys)
{
    $keys[] = GD_METAKEY_COMMENT_TAGS;
    $keys[] = GD_METAKEY_COMMENT_TAGGEDUSERS;
    return $keys;
}
