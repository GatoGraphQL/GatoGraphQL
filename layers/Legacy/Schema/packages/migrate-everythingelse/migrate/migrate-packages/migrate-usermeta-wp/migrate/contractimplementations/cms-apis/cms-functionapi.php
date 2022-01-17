<?php
namespace PoPCMSSchema\UserMeta\WP;

class FunctionAPI extends \PoPCMSSchema\UserMeta\FunctionAPI_Base
{
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
