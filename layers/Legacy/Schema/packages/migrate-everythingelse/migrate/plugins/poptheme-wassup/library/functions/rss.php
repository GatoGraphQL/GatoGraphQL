<?php
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

define('GD_URLPARAM_RSSCAMPAIGN', 'campaign');
define('GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST', 'dailypost-digest');

/**
 * How to invoke the feed:
 * Latest posts:
 * - https://agendaurbana.org/events/feed/?campaign=weekly
 */

function popGetRssPostlistCampaigns()
{
    return \PoP\Root\App::applyFilters(
        'popGetRssPostlistCampaigns',
        array(
            GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
        )
    );
}

\PoP\Root\App::addFilter('pre_get_posts', 'popthemeWassupRssFilter');
function popthemeWassupRssFilter($query)
{
    if ($query->is_feed) {
        // If it is the daily feed, then show only posts posted in the last 24 hs
        if ((\PoP\Root\App::query(GD_URLPARAM_RSSCAMPAIGN)) == GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST) {
            $query_today = array(
                'column'  => 'post_date',
                'after'   => '- 1 days'
            );
            $query->set('date_query', $query_today);
        }
    }
    return $query;
}

/**
 * Add the author link around the name when invoking 'the author' hook
 */
\PoP\Root\App::addAction('rss2_ns', 'gdRssAuthorAddlink');
function gdRssAuthorAddlink()
{
    if (is_feed()) {
        \PoP\Root\App::addFilter('the_author', 'gdRssAuthor');
    }
}
function gdRssAuthor($output)
{

    // // If it's a feed, add also the URL of the author, and give it mailchimp's formatting
    // $campaigns = array(
    //     GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
    //     GD_URLPARAM_RSSCAMPAIGN_WEEKLY,
    // );
    // if (is_feed() && in_array($_GET[GD_URLPARAM_RSSCAMPAIGN], $campaigns)) {

    // $authordata = \PoP\Root\App::getState(['routing', 'authordata'])/*global $authordata*/;
    global $authordata;
    $userTypeAPI = UserTypeAPIFacade::getInstance();
    $url = $userTypeAPI->getUserURL($authordata->ID);

    $output = sprintf(
        '<a href="%s" style="%s">%s</a>',
        $url,
        gdRssGetAuthorAnchorStyle(),
        $output
    );
    // }

    return $output;
}

function gdRssGetAuthorAnchorStyle()
{
    return \PoP\Root\App::applyFilters(
        'poptheme_wassup_rss:anchor_style',
        'word-wrap:break-word;color:#7a7a7b;font-weight:normal;text-decoration:underline;'
    );
}

\PoP\Root\App::addFilter('gdRssPrintFeaturedImage:img_attr', 'gdCustomRssFeaturedimageSize');
function gdCustomRssFeaturedimageSize($img_attr)
{

    // Change the pic dimensions for the weekly campaign
    // $campaigns = array(
    //     GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
    //     GD_URLPARAM_RSSCAMPAIGN_WEEKLY,
    // );
    // if (in_array($_GET[GD_URLPARAM_RSSCAMPAIGN], $campaigns)) {
    if (in_array(\PoP\Root\App::query(GD_URLPARAM_RSSCAMPAIGN), popGetRssPostlistCampaigns())) {
        $thumb_width = \PoP\Root\App::applyFilters(
            'poptheme_wassup_rss:thumb_width',
            132
        );
        $img_attr[2] = $thumb_width / $img_attr[1] * $img_attr[2];
        $img_attr[1] = $thumb_width;
    }

    return $img_attr;
}
