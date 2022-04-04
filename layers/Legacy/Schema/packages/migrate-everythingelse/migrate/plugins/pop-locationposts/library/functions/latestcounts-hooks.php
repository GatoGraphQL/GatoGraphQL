<?php
class PoP_LocationPosts_LatestCounts_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_Application_SectionUtils:dataloadqueryargs-latestcounts',
            $this->addLatestcount(...)
        );
    }

    public function addLatestcount($query_in_array)
    {

        // If it is in the array, then this case will already be covered automatically
        // If it is not, add support for Latest Counts for the User Stance
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (!in_array(POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            $query = &$query_in_array[0];

            $query['custompost-types'][] = POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST;

            // If not enabled, we don't query by taxonomy
            if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy()) {
                $query['tax-query'][] = array(
                    'taxonomy' => POP_LOCATIONPOSTS_TAXONOMY_CATEGORY,
                    'terms'    => array(
                        POP_LOCATIONPOSTS_CAT_ALL,
                    )
                );
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_LocationPosts_LatestCounts_Hooks();
