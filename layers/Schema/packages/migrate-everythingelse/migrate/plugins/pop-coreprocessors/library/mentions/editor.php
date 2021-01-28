<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Engine\Route\RouteUtils;

HooksAPIFacade::getInstance()->addFilter('mce_external_plugins', 'gdMentionsExternalPlugins');
function gdMentionsExternalPlugins($plugins)
{
    if (is_admin()) {
        return $plugins;
    }

    $js_folder = POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js';
    $includes_js_folder = $js_folder.'/includes';

    // tinyMCE-mention plug-in: https://github.com/CogniStreamer/tinyMCE-mention

    // Comment Leo 24/03/2016: using custom.plugin.js instead of plugin.js because we made some hacks
    // $plugins['mention'] = $includes_js_folder . '/tinymce/plugins/mention/plugin.min.js';
    $plugins['mention'] = $includes_js_folder . '/tinymce/plugins/mention/custom.plugin.min.js';

    return $plugins;
}


HooksAPIFacade::getInstance()->addFilter('teeny_mce_before_init', 'gdMentionsBeforeInit');
HooksAPIFacade::getInstance()->addFilter('tiny_mce_before_init', 'gdMentionsBeforeInit');
function gdMentionsBeforeInit($mceInit)
{
    if (is_admin() || PoP_WebPlatform_ServerUtils::disableJs()) {
        return $mceInit;
    }

    // Add the 'mentions' settings
    // Write the JSON encoded version directly, because mentions_source and mentions_insert are functions,
    // not strings, so we need no quotes around these values, but json_encode would add quotes
    // $mceInit['mentions'] = json_encode(array(
    //   'delimiter' => array('@', '#'),
    //   'source' => 'mentions_source',
    //   'insert' => 'mentions_insert',
    // ));
    $mceInit['mentions'] = sprintf(
        '{delimiter: %s, source: %s, insert: %s, render: %s, items: %s, queryBy: "%s"}',
        json_encode(array('@', '#')),
        'mentions_source',
        'mentions_insert',
        'mentions_render',
        8,
        'mentionQueryby'
    );

    return $mceInit;
}

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsMentionsManagerImpl');
function gdJqueryConstantsMentionsManagerImpl($jqueryConstants)
{
    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        // $filter_manager = \PoP\Engine\FilterManagerFactory::getInstance();

    // global $gd_filtercomponent_name, $gd_filtercomponent_orderuser, $gd_filtercomponent_ordertag;
    $query_wildcard = urlencode(GD_JSPLACEHOLDER_QUERY);

    // $filter_wildcardusers = $filter_manager->getFilter(POP_FILTER_USERS);
    $users_url = RouteUtils::getRouteURL(POP_USERS_ROUTE_USERS);
    // $filter_wildcardtags = $filter_manager->getFilter(POP_FILTER_TAGS);
    $tags_url = RouteUtils::getRouteURL(POP_POSTTAGS_ROUTE_POSTTAGS);

    // // Add a hook, so we can use the content CDN
    // $users_url = HooksAPIFacade::getInstance()->applyFilters(
    //   'pop_mentions:url:users',
    //   $users_url
    // );
    // $tags_url = HooksAPIFacade::getInstance()->applyFilters(
    //   'pop_mentions:url:tags',
    //   $tags_url
    // );
    // -------------------------------
    // User URL
    // -------------------------------
    $filter_params = array(
        [
            'module' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
            'value' => $query_wildcard,
        ],
        [
            'module' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            'value' =>  'post_count|DESC',
        ],
    );
    $users_fetchurl = \PoP\ComponentModel\DataloadUtils::addFilterParams($users_url/*, POP_FILTER_USERS*/, $filter_params);
    $users_fetchurl = PoPCore_ModuleManager_Utils::addJsonoutputResultsParams($users_fetchurl, POP_FORMAT_MENTION);

    // -------------------------------
    // User Prefetch URL
    // -------------------------------
    $filter_params = array(
        [
            'module' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            'value' =>  'post_count|DESC',
        ],
    );
    $users_baselineurl = \PoP\ComponentModel\DataloadUtils::addFilterParams($users_url/*, POP_FILTER_USERS*/, $filter_params);

    // Bring 10 times the pre-defined result set
    $users_baselineurl = GeneralUtils::addQueryArgs([
        \PoP\ComponentModel\Constants\Params::LIMIT => $cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')) * 10,
    ], $users_baselineurl);

    $users_baselineurl = PoPCore_ModuleManager_Utils::addJsonoutputResultsParams($users_baselineurl, POP_FORMAT_MENTION);

    // -------------------------------
    // Tags URL
    // -------------------------------
    $filter_params = array(
        [
            'module' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
            'value' => $query_wildcard,
        ],
        [
            'module' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERTAG],
            'value' => 'count|DESC',
        ],
    );
    $tags_fetchurl = \PoP\ComponentModel\DataloadUtils::addFilterParams($tags_url/*, POP_FILTER_TAGS*/, $filter_params);
    $tags_fetchurl = PoPCore_ModuleManager_Utils::addJsonoutputResultsParams($tags_fetchurl, POP_FORMAT_MENTION);

    // -------------------------------
    // Tags Prefetch URL
    // -------------------------------
    $filter_params = array(
        [
            'module' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERTAG],
            'value' => 'count|DESC',
        ],
    );
    $tags_baselineurl = \PoP\ComponentModel\DataloadUtils::addFilterParams($tags_url/*, POP_FILTER_USERS*/, $filter_params);

    // Bring 10 times the pre-defined result set
    $tags_baselineurl = GeneralUtils::addQueryArgs([
        \PoP\ComponentModel\Constants\Params::LIMIT => $cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')) * 10,
    ], $tags_baselineurl);

    $tags_baselineurl = PoPCore_ModuleManager_Utils::addJsonoutputResultsParams($tags_baselineurl, POP_FORMAT_MENTION);

    // -------------------------------
    // Settings
    // -------------------------------
    $jqueryConstants['MENTIONS'] = array(
        'QUERYWILDCARD' => $query_wildcard,
        'TYPES' => array(
            '@' => array(
                'url' => $users_fetchurl,
                'baseline' => $users_baselineurl,
                'module' => [PoP_Module_Processor_UserMentionComponentLayouts::class, PoP_Module_Processor_UserMentionComponentLayouts::MODULE_LAYOUTUSER_MENTION_COMPONENT],
                // Can't use "user-nicename", must use "nicename", because @Mentions plugin does not store the "-" in the html attribute, so it would
                // save the entry as data-usernicename. To avoid conflicts, just remove the "-"
                'key' => 'nicename',
            ),
            '#' => array(
                'url' => $tags_fetchurl,
                'baseline' => $tags_baselineurl,
                'module' => [PoP_Module_Processor_TagMentionComponentLayouts::class, PoP_Module_Processor_TagMentionComponentLayouts::MODULE_LAYOUTTAG_MENTION_COMPONENT],
                'key' => 'namedescription',
            ),
        ),
    );

    return $jqueryConstants;
}
