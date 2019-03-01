<?php
namespace PoP\Engine;

class MetaManager
{
    public static function getMetakeyPrefix()
    {

        // Allow to override the metakey prefix in the Theme
        if (defined('POP_METAKEY_PREFIX')) {
            return POP_METAKEY_PREFIX;
        }
        
        // Default value
        return 'pop_';
    }

    public static function getMetaKey($meta_key, $type = GD_META_TYPE_POST)
    {
        $before = '';
        if ($type == GD_META_TYPE_POST) {
            $before = '_'.self::getMetakeyPrefix();
        } elseif ($type == GD_META_TYPE_USER) {
            $before = self::getMetakeyPrefix();
        } elseif ($type == GD_META_TYPE_COMMENT) {
            $before = '_'.self::getMetakeyPrefix();
        }

        // postmeta key: add _ at the beginning
        return $before . $meta_key;
    }

    private static function normalizeValues($values)
    {
        if (!is_array($values)) {
            $values = array($values);
        }

        return array_unique(array_filter($values));
    }

    public static function getPostMeta($post_id, $key, $single = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getPostMeta($post_id, self::getMetaKey($key, GD_META_TYPE_POST), $single);
    }
    public static function updatePostMeta($post_id, $key, $values, $single = false, $boolean = false)
    {
        $values = self::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deletePostMeta($post_id, self::getMetaKey($key, GD_META_TYPE_POST));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $cmsapi->addPostMeta($post_id, self::getMetaKey($key, GD_META_TYPE_POST), $value, $single);
        }
    }
    public static function addPostMeta($post_id, $key, $value, $unique = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->addPostMeta($post_id, self::getMetaKey($key, GD_META_TYPE_POST), $value, $unique);
    }
    public static function deletePostMeta($post_id, $key, $value = '')
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deletePostMeta($post_id, self::getMetaKey($key, GD_META_TYPE_POST), $value);
    }

    public static function getTermMeta($term_id, $key, $single = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getTermMeta($term_id, self::getMetaKey($key, GD_META_TYPE_TERM), $single);
    }
    public static function updateTermMeta($term_id, $key, $values, $single = false, $boolean = false)
    {
        $values = self::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deleteTermMeta($term_id, self::getMetaKey($key, GD_META_TYPE_TERM));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $cmsapi->addTermMeta($term_id, self::getMetaKey($key, GD_META_TYPE_TERM), $value, $single);
        }
    }
    public static function addTermMeta($term_id, $key, $value, $unique = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->addTermMeta($term_id, self::getMetaKey($key, GD_META_TYPE_TERM), $value, $unique);
    }
    public static function deleteTermMeta($term_id, $key, $value = null, $unique = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deleteTermMeta($term_id, self::getMetaKey($key, GD_META_TYPE_TERM), $value, $unique);
    }

    public static function getUserMeta($user_id, $key, $single = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getUserMeta($user_id, self::getMetaKey($key, GD_META_TYPE_USER), $single);
    }
    public static function updateUserMeta($user_id, $key, $values, $single = false, $boolean = false)
    {
        $values = self::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deleteUserMeta($user_id, self::getMetaKey($key, GD_META_TYPE_USER));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $cmsapi->addUserMeta($user_id, self::getMetaKey($key, GD_META_TYPE_USER), $value, $single);
        }
    }
    public static function addUserMeta($user_id, $key, $value, $unique = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->addUserMeta($user_id, self::getMetaKey($key, GD_META_TYPE_USER), $value, $unique);
    }
    public static function deleteUserMeta($user_id, $key, $value = null, $unique = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deleteUserMeta($user_id, self::getMetaKey($key, GD_META_TYPE_USER), $value, $unique);
    }

    public static function getCommentMeta($comment_id, $key, $single = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getCommentMeta($comment_id, self::getMetaKey($key, GD_META_TYPE_COMMENT), $single);
    }
    public static function updateCommentMeta($comment_id, $key, $values, $single = false, $boolean = false)
    {
        $values = self::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deleteCommentMeta($comment_id, self::getMetaKey($key, GD_META_TYPE_COMMENT));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $cmsapi->addCommentMeta($comment_id, self::getMetaKey($key, GD_META_TYPE_COMMENT), $value, $single);
        }
    }
    public static function addCommentMeta($comment_id, $key, $value, $unique = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->addCommentMeta($comment_id, self::getMetaKey($key, GD_META_TYPE_COMMENT), $value, $unique);
    }
    public static function deleteCommentMeta($comment_id, $key, $value = null, $unique = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmsapi->deleteCommentMeta($comment_id, self::getMetaKey($key, GD_META_TYPE_COMMENT), $value, $unique);
    }
}
