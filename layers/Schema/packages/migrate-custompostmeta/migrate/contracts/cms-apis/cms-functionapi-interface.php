<?php
namespace PoPSchema\CustomPostMeta;

interface FunctionAPI
{
	public function getMetaKey($meta_key);
    public function getCustomPostMeta($post_id, $key, $single = false);
    public function deleteCustomPostMeta($post_id, $meta_key, $meta_value = '');
    public function addCustomPostMeta($post_id, $meta_key, $meta_value, $unique = false);
}
