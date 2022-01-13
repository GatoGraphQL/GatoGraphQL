<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdDataloadAllcontentTaxqueryItems()
{
    $tax_query_items = array();

    // First check if the application allows to filter content by taxonomy.
    // If true, all post types defined in `getAllcontentPostTypes` must implement a hierarchical taxonomy (category-like),
    // and make sure those posts have the corresponding term (if no term, they won't be shown in allcontent)
    if (PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy()) {
        if (PoP_Application_Taxonomy_ConfigurationUtils::hookAllcontentComponents()) {
            // Post categories
            $tax_query_items = array(
                array(
                    'taxonomy' => 'category',
                    'terms' => gdDataloadAllcontentCategories(),
                ),
            );

            // Allow to add the taxonomies for all custom types
            return HooksAPIFacade::getInstance()->applyFilters('pop_module:allcontent:tax_query_items', $tax_query_items);
        }

        // Calculate all the terms automatically, by querying the category-like taxonomies from all searchable post types,
        // and getting all the terms (categories) within
        $components = gdDataloadAllcontentComponents();
        foreach ($components as $component) {
            $tax_query_items[] = array(
                array(
                    'taxonomy' => $component['taxonomy'],
                    'terms' => $component['terms'],
                ),
            );
        }
    }

    return $tax_query_items;
}