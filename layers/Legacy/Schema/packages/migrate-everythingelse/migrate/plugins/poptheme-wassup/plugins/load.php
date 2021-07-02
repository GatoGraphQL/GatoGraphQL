<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// if (defined('POP_CONTENTPOSTLINKSWEBPLATFORM_INITIALIZED')) {
//     require_once 'pop-contentpostlinks-webplatform/load.php';
// }

if (defined('WPSC_POP_INITIALIZED')) {
    include_once 'wp-super-cache-pop/load.php';
}
if (defined('POP_SPA_INITIALIZED')) {
    include_once 'pop-spa/load.php';
}
if (defined('POP_SPAPROCESSORS_INITIALIZED')) {
    include_once 'pop-spa-processors/load.php';
}
if (defined('POP_SHAREPROCESSORS_INITIALIZED')) {
    include_once 'pop-share-processors/load.php';
}
if (defined('POP_RELATEDPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-relatedposts-processors/load.php';
}
if (defined('POP_CONTACTUSPROCESSORS_INITIALIZED')) {
    include_once 'pop-contactus-processors/load.php';
}
if (defined('POP_POSTSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-postscreation-processors/load.php';
}
if (defined('POP_ADDHIGHLIGHTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-addhighlights-processors/load.php';
}
if (defined('POP_CONTENTPOSTLINKSPROCESSORS_INITIALIZED')) {
    include_once 'pop-contentpostlinks-processors/load.php';
}
if (defined('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-contentpostlinkscreation-processors/load.php';
}
if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}
if (defined('POP_BLOGPROCESSORS_INITIALIZED')) {
    include_once 'pop-blog-processors/load.php';
}
if (defined('POP_CONTENTCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-contentcreation-processors/load.php';
}
if (defined('POP_TRENDINGTAGSPROCESSORS_INITIALIZED')) {
    include_once 'pop-trendingtags-processors/load.php';
}
if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}
if (defined('POP_USERPLATFORMPROCESSORS_INITIALIZED')) {
    include_once 'pop-userplatform-processors/load.php';
}
if (defined('POP_ADDCOMMENTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-addcomments-processors/load.php';
}
if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
    include_once 'pop-socialnetwork-processors/load.php';
}

if (defined('POP_CDN_INITIALIZED')) {
    include_once 'pop-cdn/load.php';
}

if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-application-processors/load.php';
}

if (defined('POP_USERLOGINPROCESSORS_INITIALIZED')) {
    include_once 'pop-userlogin-processors/load.php';
}

if (defined('POP_CSSCONVERTER_INITIALIZED')) {
    include_once 'pop-cssconverter/load.php';
}

if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-engine-webplatform/load.php';
}

if (defined('POP_SSR_INITIALIZED')) {
    include_once 'pop-ssr/load.php';
}
if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}

if (defined('POP_EVENTSCREATION_INITIALIZED')) {
    include_once 'pop-eventscreation/load.php';
}
if (defined('POP_EVENTLINKSCREATION_INITIALIZED')) {
    include_once 'pop-eventlinkscreation/load.php';
}
if (defined('POP_ADDLOCATIONS_INITIALIZED')) {
    include_once 'pop-addlocations/load.php';
}

if (class_exists('EM_Pro')) {
    include_once 'events-manager-pro/load.php';
}

if (defined('POP_NEWSLETTERPROCESSORS_INITIALIZED')) {
    include_once 'pop-newsletter-processors/load.php';
}
if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
    include_once 'pop-volunteering-processors/load.php';
}

if (defined('PPP_POP_INITIALIZED')) {
    include_once 'public-post-preview-pop/load.php';
}

if (defined('POP_MULTILINGUAL_INITIALIZED')) {
    include_once 'pop-multilingual/load.php';
}

if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
    include_once 'pop-usercommunities/load.php';
}

if (defined('POP_COMMONUSERROLES_INITIALIZED')) {
    include_once 'pop-commonuserroles/load.php';
}

if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
    include_once 'pop-usercommunities-processors/load.php';
}
if (defined('POP_COMMONUSERROLESPROCESSORS_INITIALIZED')) {
    include_once 'pop-commonuserroles-processors/load.php';
}

if (defined('GADWP_POP_INITIALIZED')) {
    include_once 'google-analytics-dashboard-for-wp-pop/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}

if (defined('POP_DOMAIN_INITIALIZED')) {
    include_once 'pop-domain/load.php';
}

if (defined('POP_MULTIDOMAIN_INITIALIZED')) {
    include_once 'pop-multidomain/load.php';
}

if (defined('POP_MAILER_AWS_INITIALIZED')) {
    include_once 'pop-mailer-aws/load.php';
}

if (defined('POP_USERAVATAR_INITIALIZED')) {
    include_once 'pop-useravatar/load.php';
}
if (defined('POP_USERAVATAR_AWS_INITIALIZED')) {
    include_once 'pop-useravatar-aws/load.php';
}

if (defined('POP_SERVICEWORKERS_INITIALIZED')) {
    include_once 'pop-serviceworkers/load.php';
}

if (defined('POP_NOTIFICATIONS_INITIALIZED')) {
    include_once 'pop-notifications/load.php';
}
if (defined('POP_NOTIFICATIONSPROCESSORS_INITIALIZED')) {
    include_once 'pop-notifications-processors/load.php';
}
if (defined('POP_NOTIFICATIONSPROCESSORS_INITIALIZED')) {
    include_once 'pop-notifications-processors/load.php';
}

if (defined('POP_CATEGORYPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-categoryposts-processors/load.php';
}
if (defined('POP_CATEGORYPOSTSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-categorypostscreation-processors/load.php';
}
if (defined('POP_NOSEARCHCATEGORYPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-nosearchcategoryposts-processors/load.php';
}
if (defined('POP_NOSEARCHCATEGORYPOSTSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-nosearchcategorypostscreation-processors/load.php';
}

if (defined('POP_COMMONPAGES_INITIALIZED')) {
    include_once 'pop-commonpages/load.php';
}
if (defined('POP_CLUSTERCOMMONPAGES_INITIALIZED')) {
    include_once 'pop-clustercommonpages/load.php';
}

if (defined('POP_USERSTANCEPROCESSORS_INITIALIZED')) {
    include_once 'pop-userstance-processors/load.php';
}

if (defined('POP_COMMONAUTOMATEDEMAILSPROCESSORS_INITIALIZED')) {
    include_once 'pop-commonautomatedemails-processors/load.php';
}

if (defined('ACF_POP_INITIALIZED')) {
    include_once 'advanced-custom-fields-pop/load.php';
}

if (defined('EMPOPEVENTS_INITIALIZED')) {
    include_once 'events-manager-pop-events/load.php';
}

if (defined('POP_ADDLOCATIONSPROCESSORS_INITIALIZED')) {
    include_once 'pop-addlocations-processors/load.php';
}
if (defined('POP_CONTENTCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-contentcreation-processors/load.php';
}
if (defined('POP_EVENTLINKSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-eventlinkscreation-processors/load.php';
}
if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-events-processors/load.php';
}
if (defined('POP_EVENTSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-eventscreation-processors/load.php';
}
if (defined('POP_LOCATIONPOSTLINKSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-locationpostlinkscreation-processors/load.php';
}
if (defined('POP_LOCATIONPOSTSCREATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-locationpostscreation-processors/load.php';
}
if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
    include_once 'pop-locations-processors/load.php';
}

if (defined('POP_LOCATIONPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-locationposts-processors/load.php';
}
