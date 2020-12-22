<?php
namespace PoPSchema\UserMeta;

interface FunctionAPI
{
	public function getMetaKey($meta_key);
    public function getUserMeta($user_id, $key, $single = false);
    public function deleteUserMeta($user_id, $meta_key, $meta_value = '');
    public function addUserMeta($user_id, $meta_key, $meta_value, $unique = false);
}
