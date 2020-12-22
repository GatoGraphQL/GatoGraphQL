<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Doing Ajax overriding
 */

// Events Manager add param em_ajax or em_ajax_action to pretend they are ajax calls
HooksAPIFacade::getInstance()->addFilter('gd_doing_ajax', 'gdEmDoingAjax');
function gdEmDoingAjax($doingAjax)
{
    return $doingAjax || !empty($_REQUEST['em_ajax'] ?? null) || !empty($_REQUEST['em_ajax_action'] ?? null);
}


// This is to return the message in the selected language when using ajax
HooksAPIFacade::getInstance()->addFilter('em_object_json_encode_pre', 'gdPreEmObjectJsonEncodeAddQtrans');
function gdPreEmObjectJsonEncodeAddQtrans($array)
{
    if (isset($array['message'])) {
        $array['message'] = HooksAPIFacade::getInstance()->applyFilters("gd_translate", $array['message']);
    }
    if (isset($array['errors'])) {
        $array['errors'] = HooksAPIFacade::getInstance()->applyFilters("gd_translate", $array['errors']);
    }

    return $array;
}

HooksAPIFacade::getInstance()->addFilter(
    'em_excerpt_more',
    'gdExcerptMore',
    PHP_INT_MAX,
    1
);


/* ------------------------------------------------------------------------------------
 * Strip tags off #_EVENTEXCERPT
 * ------------------------------------------------------------------------------------ */

// Remove tags and shortcodes, and shorten the excerpt
HooksAPIFacade::getInstance()->addFilter('dbem_notes_excerpt', 'gdDbemNotesExcerptStripTagsExcerpt');
function gdDbemNotesExcerptStripTagsExcerpt($result)
{
    $result = strip_tags($result);
    return limitString($result, null, null, true);
}
