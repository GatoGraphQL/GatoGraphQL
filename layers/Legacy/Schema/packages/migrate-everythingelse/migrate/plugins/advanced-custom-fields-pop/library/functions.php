<?php

function gdAcfGetKeysStoreAsArray()
{
    return \PoP\Root\App::applyFilters('gdAcfGetKeysStoreAsArray', array());
}

function gdAcfGetKeysStoreAsSingle()
{
    return \PoP\Root\App::applyFilters('gdAcfGetKeysStoreAsSingle', array());
}

// Store an array in different non-unique rows
\PoP\Root\App::addFilter('acf/update_value', 'gdAcfUpdateValue', 10, 3);
function gdAcfUpdateValue($value, $post_id, $field)
{
    $key = $field['name'];
    if (in_array($key, gdAcfGetKeysStoreAsArray())) {
        if (is_numeric($post_id)) {
            \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($post_id, $key, $value);
        } elseif (strpos($post_id, 'user_') !== false) {
            $user_id = str_replace('user_', '', $post_id);
            \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, $key, $value);
        }
    } elseif (in_array($key, gdAcfGetKeysStoreAsSingle())) {
        if (is_numeric($post_id)) {
            \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($post_id, $key, $value, true);
        } elseif (strpos($post_id, 'user_') !== false) {
            $user_id = str_replace('user_', '', $post_id);
            \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, $key, $value, true);
        }
    }

    return $value;
}

function gdAcfLoadValue($value, $post_id, $field, $keys, $single = false)
{
    $key = $field['name'];

    if (in_array($key, $keys)) {
        // if $post_id is a string, then it is used in the everything fields and can be found in the options table
        if (is_numeric($post_id)) {
            return \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($post_id, $key, $single);
        } elseif (strpos($post_id, 'user_') !== false) {
            $user_id = str_replace('user_', '', $post_id);
            return \PoPCMSSchema\UserMeta\Utils::getUserMeta($user_id, $key, $single);
        }
    }

    return $value;
}


\PoP\Root\App::addFilter('acf/load_value', 'gdAcfLoadValueArray', 100, 3);
function gdAcfLoadValueArray($value, $post_id, $field)
{
    return gdAcfLoadValue($value, $post_id, $field, gdAcfGetKeysStoreAsArray());
}

\PoP\Root\App::addFilter('acf/load_value', 'gdAcfLoadValueSingle', 100, 3);
function gdAcfLoadValueSingle($value, $post_id, $field)
{
    return gdAcfLoadValue($value, $post_id, $field, gdAcfGetKeysStoreAsSingle(), true);
}
