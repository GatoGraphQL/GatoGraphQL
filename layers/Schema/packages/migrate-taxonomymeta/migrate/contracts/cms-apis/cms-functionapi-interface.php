<?php
namespace PoPSchema\TaxonomyMeta;

interface FunctionAPI
{
	public function getMetaKey($meta_key);
    public function getTermMeta($term_id, $key, $single = false);
    public function deleteTermMeta($term_id, $meta_key, $meta_value = '');
    public function addTermMeta($term_id, $meta_key, $meta_value, $unique = false);
}
