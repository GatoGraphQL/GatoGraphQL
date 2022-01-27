<?php
namespace PoPCMSSchema\TaxonomyMeta\WP;

class FunctionAPI extends \PoPCMSSchema\TaxonomyMeta\FunctionAPI_Base
{
    public function deleteTermMeta($term_id, $meta_key, $meta_value = '')
    {
        return delete_term_meta($term_id, $meta_key, $meta_value);
    }
    public function addTermMeta($term_id, $meta_key, $meta_value, $unique = false)
    {
        return add_term_meta($term_id, $meta_key, $meta_value, $unique);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
