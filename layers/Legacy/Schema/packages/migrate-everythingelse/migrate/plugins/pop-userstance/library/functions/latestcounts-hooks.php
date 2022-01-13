<?php
class PoP_UserStance_LatestCounts_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
            'PoP_Application_SectionUtils:dataloadqueryargs-latestcounts',
            array($this, 'addUserstance')
        );
    }

    public function addUserstance($query_in_array)
    {

        // If it is in the array, then this case will already be covered automatically
        // If it is not, add support for Latest Counts for the User Stance
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (!in_array(POP_USERSTANCE_POSTTYPE_USERSTANCE, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            $query = &$query_in_array[0];

            $query['custompost-types'][] = POP_USERSTANCE_POSTTYPE_USERSTANCE;

            // If not enabled, we don't query by taxonomy
            if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy()) {
                $query['tax-query'][] = array(
                    'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                    'terms'    => array(
                        POP_USERSTANCE_TERM_STANCE_PRO,
                        POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                        POP_USERSTANCE_TERM_STANCE_AGAINST,
                    )
                );
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_UserStance_LatestCounts_Hooks();
