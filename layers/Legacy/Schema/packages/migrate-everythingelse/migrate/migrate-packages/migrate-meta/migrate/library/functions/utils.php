<?php
namespace PoPCMSSchema\Meta;

class Utils
{
    public static function getMetakeyPrefix()
    {

        // Allow to override the metakey prefix in the Theme
        if (defined('POP_METAKEY_PREFIX')) {
            return POP_METAKEY_PREFIX;
        }
        
        return '';
    }

    public static function normalizeValues($values)
    {
        if (!is_array($values)) {
            $values = array($values);
        }

        return array_unique(array_filter($values));
    }
}
