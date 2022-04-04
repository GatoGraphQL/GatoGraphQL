<?php

\PoP\Root\App::addFilter('gdAcfGetKeysStoreAsArray', gdPopureAcfGetKeysStoreAsArrayCustomImpl(...));
function gdPopureAcfGetKeysStoreAsArrayCustomImpl($keys)
{
    $keys[] = GD_URE_METAKEY_PROFILE_COMMUNITIES;
    return $keys;
}
