<?php

\PoP\Root\App::getHookManager()->addFilter('gdAcfGetKeysStoreAsArray', 'gdAcfGetKeysStoreAsArrayPosts');
function gdAcfGetKeysStoreAsArrayPosts($keys)
{
    $keys[] = GD_METAKEY_POST_REFERENCES;
    return $keys;
}
