<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * No need from any avatar size from this plugin
 */
HooksAPIFacade::getInstance()->addFilter('coauthors_guest_author_avatar_sizes', 'emptyArray', 10000);
function emptyArray($anything)
{
    return array();
}

HooksAPIFacade::getInstance()->addFilter('CMSAPI:customposts:query', 'maybeRemoveSupressFilters');
function maybeRemoveSupressFilters($query)
{
	// If filtering by author, let it also be a co-author
	if (isset($query['author'])) {
	    // Allow for co-authors plus plug-in to add "posts_where_filter"
	    $query['suppress_filters'] = false;
	}
    return $query;
}
