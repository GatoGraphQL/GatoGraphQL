<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'wassupAcfGetKeysStoreAsArrayCustom');
function wassupAcfGetKeysStoreAsArrayCustom($keys)
{
    $keys[] = GD_METAKEY_POST_LINKCATEGORIES;
    $keys[] = GD_METAKEY_POST_CATEGORIES;
    $keys[] = GD_METAKEY_POST_APPLIESTO;
    return $keys;
}

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsSingle', 'wassupAcfGetKeysStoreAsSingleCustom');
function wassupAcfGetKeysStoreAsSingleCustom($keys)
{
    $keys[] = GD_METAKEY_POST_LINKACCESS;
    return $keys;
}
