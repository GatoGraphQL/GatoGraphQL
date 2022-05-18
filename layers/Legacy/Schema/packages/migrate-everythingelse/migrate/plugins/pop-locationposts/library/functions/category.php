<?php
use PoPCMSSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;

/**
 * Integration with Latest Everything Block
 */
\PoP\Root\App::addFilter('pop_componentVariation:allcontent:tax_query_items', 'popLocationpostsSearchablecontentTaxquery');
function popLocationpostsSearchablecontentTaxquery($tax_query_items)
{
    if (POP_LOCATIONPOSTS_CAT_ALL) {
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array(POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            $tax_query_items[] = array(
                'taxonomy' => POP_LOCATIONPOSTS_TAXONOMY_CATEGORY,
                'terms' => array_filter(
                    array(
                        POP_LOCATIONPOSTS_CAT_ALL,
                    )
                ),
            );
        }
    }

    return $tax_query_items;
}

/**
 * Needed to add the "All" category to all events, to list them for the Latest Everything Block
 */
// Do always add the 'All' Category when creating/updating a locationpost
\PoP\Root\App::addAction('init', 'popLocationpostsInitAddAllCategory');
function popLocationpostsInitAddAllCategory()
{
    \PoP\Root\App::addAction('save_post_'.POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST, 'popLocationpostsAddAllCategory', 10, 1);
}
function popLocationpostsAddAllCategory($post_id)
{
    // Only do it if filtering by taxonomy is enabled. Otherwise no need
    if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy()) {
        $taxonomyapi = TaxonomyTypeAPIFacade::getInstance();
        if (POP_LOCATIONPOSTS_CAT_ALL && !$taxonomyapi->hasTerm(POP_LOCATIONPOSTS_CAT_ALL, POP_LOCATIONPOSTS_TAXONOMY_CATEGORY, $post_id)) {
            $taxonomyapi->setPostTerms($post_id, array(POP_LOCATIONPOSTS_CAT_ALL), POP_LOCATIONPOSTS_TAXONOMY_CATEGORY, true);
        }
    }
}
