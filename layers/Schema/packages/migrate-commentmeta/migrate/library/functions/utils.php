<?php
namespace PoPSchema\CommentMeta;

class Utils
{
    public static function getMetaKey($meta_key)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        return $functionapi->getMetaKey(\PoPSchema\Meta\Utils::getMetakeyPrefix().$meta_key);
    }

    public static function getCommentMeta($comment_id, $key, $single = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        return $functionapi->getCommentMeta($comment_id, self::getMetaKey($key), $single);
    }
    public static function updateCommentMeta($comment_id, $key, $values, $single = false, $boolean = false)
    {
        $values = \PoPSchema\Meta\Utils::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteCommentMeta($comment_id, self::getMetaKey($key));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $functionapi->addCommentMeta($comment_id, self::getMetaKey($key), $value, $single);
        }
    }
    public static function addCommentMeta($comment_id, $key, $value, $unique = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->addCommentMeta($comment_id, self::getMetaKey($key), $value, $unique);
    }
    public static function deleteCommentMeta($comment_id, $key, $value = null, $unique = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteCommentMeta($comment_id, self::getMetaKey($key), $value, $unique);
    }
}
