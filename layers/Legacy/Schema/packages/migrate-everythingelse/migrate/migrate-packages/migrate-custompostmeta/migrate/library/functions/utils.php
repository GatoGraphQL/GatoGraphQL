<?php
namespace PoPCMSSchema\CustomPostMeta;

use PoPCMSSchema\CustomPostMeta\Facades\CustomPostMetaTypeAPIFacade;

class Utils
{
    public static function getMetaKey($meta_key)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        return $functionapi->getMetaKey(\PoPCMSSchema\Meta\Utils::getMetakeyPrefix().$meta_key);
    }

    public static function getCustomPostMeta($post_id, $key, $single = false)
    {
        $customPostMetaTypeAPI = CustomPostMetaTypeAPIFacade::getInstance();
        return $customPostMetaTypeAPI->getCustomPostMeta($post_id, self::getMetaKey($key), $single);
    }
    public static function updateCustomPostMeta($post_id, $key, $values, $single = false, $boolean = false)
    {
        $values = \PoPCMSSchema\Meta\Utils::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteCustomPostMeta($post_id, self::getMetaKey($key));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $functionapi->addCustomPostMeta($post_id, self::getMetaKey($key), $value, $single);
        }
    }
    public static function addCustomPostMeta($post_id, $key, $value, $unique = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->addCustomPostMeta($post_id, self::getMetaKey($key), $value, $unique);
    }
    public static function deleteCustomPostMeta($post_id, $key, $value = '')
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteCustomPostMeta($post_id, self::getMetaKey($key), $value);
    }
}
