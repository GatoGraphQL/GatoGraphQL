<?php
namespace PoPSchema\CustomPostMeta\WP;

class FunctionAPI extends \PoPSchema\CustomPostMeta\FunctionAPI_Base
{
    public function getMetaKey($meta_key)
    {
        return '_'.$meta_key;
    }
    public function getCustomPostMeta($post_id, $key, $single = false)
    {
        return get_post_meta($post_id, $key, $single);
    }
    public function deleteCustomPostMeta($post_id, $meta_key, $meta_value = '')
    {
        return delete_post_meta($post_id, $meta_key, $meta_value);
    }
    public function addCustomPostMeta($post_id, $meta_key, $meta_value, $unique = false)
    {
        return add_post_meta($post_id, $meta_key, $meta_value, $unique);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
