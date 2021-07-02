<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

define('GD_URLPARAM_RSSCAMPAIGN', 'campaign');
define('GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST', 'dailypost-digest');

/**
 * How to invoke the feed:
 * Latest posts:
 * - https://agendaurbana.org/events/feed/?campaign=weekly
 */

function popGetRssPostlistCampaigns()
{
    return HooksAPIFacade::getInstance()->applyFilters(
        'popGetRssPostlistCampaigns',
        array(
            GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
        )
    );
}

HooksAPIFacade::getInstance()->addFilter('pre_get_posts', 'popthemeWassupRssFilter');
function popthemeWassupRssFilter($query)
{
    if ($query->is_feed) {
        // If it is the daily feed, then show only posts posted in the last 24 hs
        if (($_REQUEST[GD_URLPARAM_RSSCAMPAIGN] ?? null) == GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST) {
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
HooksAPIFacade::getInstance()->addAction('rss2_ns', 'gdRssAuthorAddlink');
function gdRssAuthorAddlink()
{
    if (is_feed()) {
        HooksAPIFacade::getInstance()->addFilter('the_author', 'gdRssAuthor');
    }
}
function gdRssAuthor($output)
{

    // // If it's a feed, add also the URL of the author, and give it mailchimp's formatting
    // $campaigns = array(
    //     GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
    //     GD_URLPARAM_RSSCAMPAIGN_WEEKLY,
    // );
    // if (is_feed() && in_array($_REQUEST[GD_URLPARAM_RSSCAMPAIGN], $campaigns)) {

    // $vars = ApplicationState::getVars();
    // $authordata = $vars['routing-state']['authordata']/*global $authordata*/;
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
    return HooksAPIFacade::getInstance()->applyFilters(
        'poptheme_wassup_rss:anchor_style',
        'word-wrap:break-word;color:#7a7a7b;font-weight:normal;text-decoration:underline;'
    );
}

HooksAPIFacade::getInstance()->addFilter('gdRssPrintFeaturedImage:img_attr', 'gdCustomRssFeaturedimageSize');
function gdCustomRssFeaturedimageSize($img_attr)
{

    // Change the pic dimensions for the weekly campaign
    // $campaigns = array(
    //     GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
    //     GD_URLPARAM_RSSCAMPAIGN_WEEKLY,
    // );
    // if (in_array($_REQUEST[GD_URLPARAM_RSSCAMPAIGN], $campaigns)) {
    if (in_array($_REQUEST[GD_URLPARAM_RSSCAMPAIGN] ?? null, popGetRssPostlistCampaigns())) {
        $thumb_width = HooksAPIFacade::getInstance()->applyFilters(
            'poptheme_wassup_rss:thumb_width',
            132
        );
        $img_attr[2] = $thumb_width / $img_attr[1] * $img_attr[2];
        $img_attr[1] = $thumb_width;
    }

    return $img_attr;
}
