<?php
namespace PoPSchema\UserMeta\WP;

class FunctionAPI extends \PoPSchema\UserMeta\FunctionAPI_Base
{
    public function getUserMeta($user_id, $key, $single = false)
    {
        return get_user_meta($user_id, $key, $single);
    }
    public function deleteUserMeta($user_id, $meta_key, $meta_value = '')
    {
        return delete_user_meta($user_id, $meta_key, $meta_value);
    }
    public function addUserMeta($user_id, $meta_key, $meta_value, $unique = false)
    {
        return add_user_meta($user_id, $meta_key, $meta_value, $unique);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
