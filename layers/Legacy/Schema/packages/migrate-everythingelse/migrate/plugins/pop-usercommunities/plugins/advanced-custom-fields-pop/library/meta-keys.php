<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gdAcfGetKeysStoreAsArray', 'gdPopureAcfGetKeysStoreAsArrayCustomImpl');
function gdPopureAcfGetKeysStoreAsArrayCustomImpl($keys)
{
    $keys[] = GD_URE_METAKEY_PROFILE_COMMUNITIES;
    return $keys;
}
