<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('GD_URLPARAM_SWNETWORKFIRST', 'sw-networkfirst');

/**
 * Important: this same value must be set in the .htaccess to make an internal redirect, so the cache bust parameter
 * is ignored when doing the request, so allowing WP Super Cache to return a cached version
 * .htaccess code:
 *
 *     # case: leading and trailing parameters
 *     RewriteCond %{QUERY_STRING} ^(.+)&sw-cachebust=[0-9a-z]+&(.+)$ [NC]
 *     RewriteRule (.*) /$1?%1&%2 [L]
 *     # case: leading-only, trailing-only or no additional parameters
 *     RewriteCond %{QUERY_STRING} ^(.+)&sw-cachebust=[0-9a-z]+$|^cachebust=[0-9a-z]+&?(.*)$ [NC]
 *     RewriteRule (.*) /$1?%1 [L]
 */
define('GD_URLPARAM_SWCACHEBUST', 'sw-cachebust');

HooksAPIFacade::getInstance()->addFilter('RequestUtils:current_url:remove_params', 'popSwRemoveUrlparams');
function popSwRemoveUrlparams($remove_params)
{
    $remove_params[] = GD_URLPARAM_SWNETWORKFIRST;
    $remove_params[] = GD_URLPARAM_SWCACHEBUST;

    return $remove_params;
}

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popSwJqueryConstants');
function popSwJqueryConstants($jqueryConstants)
{
    $jqueryConstants['SW_URLPARAM_NETWORKFIRST'] = GD_URLPARAM_SWNETWORKFIRST;
    // $jqueryConstants['SW_IDS_CHECKBOX_REMEMBER'] = POP_SW_IDS_CHECKBOX_REMEMBER;


    // Allow the PoPTheme Wassup indicate in which pagesections will show the "Please refresh this page" message
    $jqueryConstants['SW_MAIN_PAGESECTIONS'] = HooksAPIFacade::getInstance()->applyFilters('pop_sw_main_pagesection_container_ids', array());

    // We don't want to fetch it from the network, but from the cache, so remove the filter that we've added

    HooksAPIFacade::getInstance()->removeFilter('getReloadurlLinkattrs', 'popSwReloadurlLinkattrs');
    $reloadurl_linkattrs = getReloadurlLinkattrs();
    HooksAPIFacade::getInstance()->addFilter('getReloadurlLinkattrs', 'popSwReloadurlLinkattrs');

    // The message html to be appended to the pageSection
    $msg_placeholder = '<div class="pop-notificationmsg %s alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" aria-hidden="true" data-dismiss="alert">×</button>%s</div>';
    $message = sprintf(
        $msg_placeholder,
        'page-level',
        sprintf(
            TranslationAPIFacade::getInstance()->__('This page has been updated, please <a href="%s" target="%s" %s>click here to refresh it</a>.', 'pop-serviceworkers'),
            '{0}',
            '{1}',
            $reloadurl_linkattrs
        )
    );
    $jqueryConstants['SW_MESSAGES_PAGEUPDATED'] = HooksAPIFacade::getInstance()->applyFilters('pop_sw_message:pageupdated', $message);

    // The "there is a new SW" message html to be appended to the status
    // 'topmost': show on top of any other message
    $message = sprintf(
        $msg_placeholder,
        'website-level topmost',
        sprintf(
            TranslationAPIFacade::getInstance()->__('There is a new version of the website, please <a href="%s" target="%s">click here to reload it</a>.', 'pop-serviceworkers'),
            '{0}',
            GD_URLPARAM_TARGET_FULL
        )
    );
    $jqueryConstants['SW_MESSAGES_WEBSITEUPDATED'] = HooksAPIFacade::getInstance()->applyFilters('pop_sw_message:websiteupdated', $message);

    return $jqueryConstants;
}


HooksAPIFacade::getInstance()->addFilter('getReloadurlLinkattrs', 'popSwReloadurlLinkattrs');
HooksAPIFacade::getInstance()->addFilter('GD_DataLoad_ActionExecuter_CreateUpdate_UserBase:success_msg:linkattrs', 'popSwReloadurlLinkattrs');
function popSwReloadurlLinkattrs($params)
{
    $params .= ' data-sw-networkfirst="true"';
    return $params;
}
