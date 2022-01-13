<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAcfGetKeysStoreAsArray', 'gdUreAcfGetKeysStoreAsArrayCustomImpl');
function gdUreAcfGetKeysStoreAsArrayCustomImpl($keys)
{
    $keys[] = GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES;
    $keys[] = GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES;
    $keys[] = GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS;
    return $keys;
}
