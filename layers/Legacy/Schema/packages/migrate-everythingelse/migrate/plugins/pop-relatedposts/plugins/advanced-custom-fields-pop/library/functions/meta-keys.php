<?php

\PoP\Root\App::addFilter('gdAcfGetKeysStoreAsArray', 'gdAcfGetKeysStoreAsArrayPosts');
function gdAcfGetKeysStoreAsArrayPosts($keys)
{
    $keys[] = GD_METAKEY_POST_REFERENCES;
    return $keys;
}
