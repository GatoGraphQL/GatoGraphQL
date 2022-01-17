<?php
namespace PoPCMSSchema\UserMeta;

use PoPCMSSchema\UserMeta\Facades\UserMetaTypeAPIFacade;

class Utils
{
    public static function getMetaKey($meta_key)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        return $functionapi->getMetaKey(\PoPCMSSchema\Meta\Utils::getMetakeyPrefix().$meta_key);
    }

    public static function getUserMeta($user_id, $key, $single = false)
    {
        $userMetaTypeAPI = UserMetaTypeAPIFacade::getInstance();
        return $userMetaTypeAPI->getUserMeta($user_id, self::getMetaKey($key), $single);
    }
    public static function updateUserMeta($user_id, $key, $values, $single = false, $boolean = false)
    {
        $values = \PoPCMSSchema\Meta\Utils::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteUserMeta($user_id, self::getMetaKey($key));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $functionapi->addUserMeta($user_id, self::getMetaKey($key), $value, $single);
        }
    }
    public static function addUserMeta($user_id, $key, $value, $unique = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->addUserMeta($user_id, self::getMetaKey($key), $value, $unique);
    }
    public static function deleteUserMeta($user_id, $key, $value = null, $unique = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteUserMeta($user_id, self::getMetaKey($key), $value, $unique);
    }
}
