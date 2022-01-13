<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gdAcfGetKeysStoreAsArray', 'wassupAcfGetKeysStoreAsArrayCustom');
function wassupAcfGetKeysStoreAsArrayCustom($keys)
{
    $keys[] = GD_METAKEY_POST_LINKCATEGORIES;
    $keys[] = GD_METAKEY_POST_CATEGORIES;
    $keys[] = GD_METAKEY_POST_APPLIESTO;
    return $keys;
}

\PoP\Root\App::getHookManager()->addFilter('gdAcfGetKeysStoreAsSingle', 'wassupAcfGetKeysStoreAsSingleCustom');
function wassupAcfGetKeysStoreAsSingleCustom($keys)
{
    $keys[] = GD_METAKEY_POST_LINKACCESS;
    return $keys;
}
