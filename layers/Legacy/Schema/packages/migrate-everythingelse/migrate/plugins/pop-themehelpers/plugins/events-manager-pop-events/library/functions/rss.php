<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Add the Featured Image to the feed
 */
HooksAPIFacade::getInstance()->addAction('em:rss2_ns', 'gdRssNamespace');
HooksAPIFacade::getInstance()->addAction('em:rss2_item', 'gdEmRssFeaturedImage', 10, 1);
function gdEmRssFeaturedImage($EM_Event)
{
    gdRssPrintFeaturedImage($EM_Event->post_id);
}


/**
 * Author URL for events
 */
HooksAPIFacade::getInstance()->addFilter('gdEmRss:author', 'gdEmRssAuthor', 10, 2);
function gdEmRssAuthor($output, $EM_Event)
{
    $url = $EM_Event->output('#_EVENTAUTHORURL');
    $output = sprintf(
        '<a href="%s" style="%s">%s</a>',
        $url,
        gdRssGetAuthorAnchorStyle(),
        $output
    );

    return $output;
}
