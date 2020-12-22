<?php
namespace PoPSchema\TaxonomyQuery\WP;

class FunctionAPIUtils
{
    public static function convertTaxonomyQuery($query)
    {
        if (isset($query['tax-query'])) {
            
            $query['tax_query'] = $query['tax-query'];
            // // Make sure the "relation" has not become an array from merging the tax_query values together
            // if (isset($query['tax_query']['relation']) && is_array($query['tax_query']['relation'])) {
            //     $query['tax_query']['relation'] = $query['tax_query']['relation'][0];
            // }
            unset($query['tax-query']);
        }
        return $query;
    }
}