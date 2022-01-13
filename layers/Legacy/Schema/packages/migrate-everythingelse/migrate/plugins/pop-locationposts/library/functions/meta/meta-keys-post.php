<?php

const GD_METAKEY_POST_LOCATIONPOSTCATEGORIES = 'locationpostcategories';

\PoP\Root\App::getHookManager()->addFilter('gd_acf_em_get_keys_store_as_array', 'gdAcfEmGetKeysStoreAsArrayCustomImpl');
function gdAcfEmGetKeysStoreAsArrayCustomImpl($keys)
{
    $keys[] = GD_METAKEY_POST_LOCATIONPOSTCATEGORIES;
    return $keys;
}
