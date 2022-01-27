<?php
namespace PoPCMSSchema\TaxonomyMeta;

use PoPCMSSchema\TaxonomyMeta\Facades\TaxonomyMetaTypeAPIFacade;

class Utils
{
    public static function getMetaKey($meta_key)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        return $functionapi->getMetaKey(\PoPCMSSchema\Meta\Utils::getMetakeyPrefix().$meta_key);
    }

    public static function getTermMeta($term_id, $key, $single = false)
    {
        $taxonomyMetaTypeAPI = TaxonomyMetaTypeAPIFacade::getInstance();
        return $taxonomyMetaTypeAPI->getTaxonomyTermMeta($term_id, self::getMetaKey($key), $single);
    }
    public static function updateTermMeta($term_id, $key, $values, $single = false, $boolean = false)
    {
        $values = \PoPCMSSchema\Meta\Utils::normalizeValues($values);

        // Add the values as independent values so each one of them can be searched using EXISTS on WP_Query
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteTermMeta($term_id, self::getMetaKey($key));
        foreach ($values as $value) {
            // If dealing with boolean values, do not save a "false" in the DB, so we can use "EXISTS" to find all the entries with "true"
            if ($boolean && !$value) {
                continue;
            }
            $functionapi->addTermMeta($term_id, self::getMetaKey($key), $value, $single);
        }
    }
    public static function addTermMeta($term_id, $key, $value, $unique = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->addTermMeta($term_id, self::getMetaKey($key), $value, $unique);
    }
    public static function deleteTermMeta($term_id, $key, $value = null, $unique = false)
    {
        $functionapi = FunctionAPIFactory::getInstance();
        $functionapi->deleteTermMeta($term_id, self::getMetaKey($key), $value, $unique);
    }
}
