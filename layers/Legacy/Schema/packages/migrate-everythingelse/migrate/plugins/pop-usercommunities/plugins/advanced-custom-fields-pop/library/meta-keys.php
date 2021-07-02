<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'gdPopureAcfGetKeysStoreAsArrayCustomImpl');
function gdPopureAcfGetKeysStoreAsArrayCustomImpl($keys)
{
    $keys[] = GD_URE_METAKEY_PROFILE_COMMUNITIES;
    return $keys;
}
