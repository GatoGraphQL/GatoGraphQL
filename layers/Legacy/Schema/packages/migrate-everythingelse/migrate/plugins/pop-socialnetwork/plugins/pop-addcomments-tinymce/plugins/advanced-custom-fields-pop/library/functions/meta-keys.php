<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gdAcfGetKeysStoreAsArray', 'gdAcfGetKeysStoreAsArrayComments');
function gdAcfGetKeysStoreAsArrayComments($keys)
{
    $keys[] = GD_METAKEY_COMMENT_TAGS;
    $keys[] = GD_METAKEY_COMMENT_TAGGEDUSERS;
    return $keys;
}
