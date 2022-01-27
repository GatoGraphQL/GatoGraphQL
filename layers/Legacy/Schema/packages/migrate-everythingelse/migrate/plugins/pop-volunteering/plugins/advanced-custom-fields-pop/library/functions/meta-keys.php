<?php

\PoP\Root\App::addFilter('gdAcfGetKeysStoreAsSingle', 'gdAcfGetKeysStoreAsSingleCustomImpl');
function gdAcfGetKeysStoreAsSingleCustomImpl($keys)
{
    $keys[] = GD_METAKEY_POST_VOLUNTEERSNEEDED;
    return $keys;
}
