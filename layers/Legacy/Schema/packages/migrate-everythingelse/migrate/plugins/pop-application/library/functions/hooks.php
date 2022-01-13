<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Application_Hooks
{
	public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
            'PoP_Application_SectionUtils:dataloadqueryargs-allcontent',
            array($this, 'addAllcontentQueryargs')
        );

        \PoP\Root\App::getHookManager()->addAction(
            'PoP_Application_SectionUtils:dataloadqueryargs-allcontent-bysingletag',
            array($this, 'addAllcontentBySingleTagQueryargs')
        );

        \PoP\Root\App::getHookManager()->addFilter(
            'latestcounts:allcontent:classes',
            array($this, 'getAllcontentClasses')
        );
    }

    public function getAllcontentClasses($classes)
    {
        if (!(defined('POP_TAXONOMIES_INITIALIZED') && PoP_Application_Taxonomy_ConfigurationUtils::hookAllcontentComponents())) {

        	// First remove the post types as classes, and add them again with their terms
        	if ($components = gdDataloadAllcontentComponents()) {

                $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        		$classes = array_values(array_diff(
        			$classes,
        			$cmsapplicationpostsapi->getAllcontentPostTypes()
        		));
            
	            // Calculate all the terms automatically, by querying the category-like taxonomies from all searchable post types,
		        // and getting all the terms (categories) within
		        foreach ($components as $component) {
		            // If it has categories, use it. Otherwise, only use the post type
		            if ($terms = $component['terms']) {
		                foreach ($terms as $term) {
		                    $classes[] = $component['postType'].'-'.$term;
		                }
		            } else {
		                $classes[] = $component['postType'];
		            }
		        }
		    }
        }

        return $classes;
    }

    public function addAllcontentQueryargs($ret_in_array)
    {
        $ret = &$ret_in_array[0];
        if (defined('POP_TAXONOMYQUERY_INITIALIZED')) {
            if ($tax_query_items = gdDataloadAllcontentTaxqueryItems()) {
                $ret['tax-query'] = array_merge(
                    array(
                        'relation' => 'OR'
                    ),
                    $tax_query_items
                );
            }
        }
    }

    public function addAllcontentBySingleTagQueryargs($ret_in_array)
    {
        $ret = &$ret_in_array[0];
        // Must create a nested taxonomy (https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters),
        // where in each 'AND' item we query for the post category/event and the tag
        if (defined('POP_TAXONOMYQUERY_INITIALIZED')) {
            if ($tax_query_items = gdDataloadAllcontentTaxqueryItems()) {
            	
            	$tag_ids = $ret['tag-ids'];
            	unset($ret['tag-ids']);

                $conf_allcontent_taxquery = array(
                    'relation' => 'OR',
                );
                foreach ($tax_query_items as $tax_query_item) {
                    $item = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'post_tag',
                            'terms' => $tag_ids,
                        )
                    );
                    $item[] = $tax_query_item;
                    $conf_allcontent_taxquery[] = $item;
                }
                $ret['tax-query'] = $conf_allcontent_taxquery;
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_Application_Hooks();
