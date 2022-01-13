<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsSingle', 'gdAcfGetKeysStoreAsSingleCustomImpl');
function gdAcfGetKeysStoreAsSingleCustomImpl($keys)
{
    $keys[] = GD_METAKEY_POST_VOLUNTEERSNEEDED;
    return $keys;
}
