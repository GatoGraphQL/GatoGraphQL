<?php

/**
 * Standard
 */

\PoP\Root\App::getHookManager()->addFilter('pre_get_posts', 'gdRssFilter');
function gdRssFilter($query)
{

    // What posts to show on the feed
    if ($query->is_feed) {
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if ($post_types = $cmsapplicationpostsapi->getAllcontentPostTypes()) {
            $query->set('post_type', $post_types);
        }
        if ($tax_query_items = gdDataloadAllcontentTaxqueryItems()) {
            $tax_query = array_merge(
                array(
                    'relation' => 'OR'
                ),
                $tax_query_items
            );
            $query->set('tax_query', $tax_query);
        }
    }
    return $query;
}
