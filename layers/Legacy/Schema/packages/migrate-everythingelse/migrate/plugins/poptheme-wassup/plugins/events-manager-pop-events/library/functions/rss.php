<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_3DAYS', 'events-3days');
define('GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_4DAYS', 'events-4days');

\PoP\Root\App::getHookManager()->addFilter('popGetRssPostlistCampaigns', 'popEmGetRssPostlistCampaigns');
function popEmGetRssPostlistCampaigns($campaigns)
{
    return array_merge(
        $campaigns,
        array(
            GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_3DAYS,
            GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_4DAYS,
        )
    );
}

/**
 * Scope for getting events
 */
\PoP\Root\App::getHookManager()->addFilter('em_rss_template_args', 'popEmRssTemplateArgs');
function popEmRssTemplateArgs($args)
{
    if (isset($_REQUEST[GD_URLPARAM_RSSCAMPAIGN])) {
        // Change the scope
        $scope_days = array(
            GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_3DAYS => 3,
            GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_4DAYS => 4,
        );
        if ($days = $scope_days[$_REQUEST[GD_URLPARAM_RSSCAMPAIGN]]) {
            $args['scope'] = $days.'-days';
        }
    }

    return $args;
}

function gdEmRss()
{
    global $wp_query;

    // Taken from plugins/events-manager/events-manager.php function emRss()
    // Calling https://www.mesym.com/events/feed/ does not produce the EM feed
    // So correct it here by checking if we're in the EM_URI
    $requestHelperService = RequestHelperServiceFacade::getInstance();
    if (\is_feed() && strpos($requestHelperService->getRequestedFullURL(), \EM_URI) === 0) {
        //events feed - show it all
        $wp_query->is_feed = true; //make is_feed() return true AIO SEO fix
        ob_start();
        \em_locate_template('templates/rss.php', true, array('args'=>$args));
        echo \PoP\Root\App::getHookManager()->applyFilters('emRss', ob_get_clean());
        die();
    }
}
\PoP\Root\App::getHookManager()->addAction('template_redirect', 'gdEmRss');
