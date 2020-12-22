<?php
namespace PoPSchema\MetaQuery\WP;

class FunctionAPIUtils
{
    public static function convertMetaQuery($query)
    {
        if (isset($query['meta-query'])) {
            
            $query['meta_query'] = $query['meta-query'];
            unset($query['meta-query']);
        }
        return $query;
    }
}