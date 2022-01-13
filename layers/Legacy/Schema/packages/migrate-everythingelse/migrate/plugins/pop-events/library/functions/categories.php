<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Needed to add the "All" category to all events, to list them for the Latest Everything Block
 */

// Do always add the 'All' Category when adding a new event
\PoP\Root\App::getHookManager()->addAction('em_event_save_pre', 'gdEmEventSavePreAddAllCategory', 10, 1);
function gdEmEventSavePreAddAllCategory($EM_Event)
{

    // Only do it if filtering by taxonomy is enabled. Otherwise no need
    if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy()) {
        if (defined('POP_EVENTS_CAT_ALL') && POP_EVENTS_CAT_ALL) {
            if (!$EM_Event->get_categories()->terms[POP_EVENTS_CAT_ALL]) {
                $EM_Event->get_categories()->terms[POP_EVENTS_CAT_ALL] = new EM_Category(POP_EVENTS_CAT_ALL);
            }
        }
    }
}
