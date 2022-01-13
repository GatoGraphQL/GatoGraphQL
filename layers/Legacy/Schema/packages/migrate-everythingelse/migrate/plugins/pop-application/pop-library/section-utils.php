<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Application_SectionUtils
{
    public static function addDataloadqueryargsLatestcounts(&$ret)
    {
        self::addDataloadqueryargsAllcontent($ret);

        // Allow Non-All Content blocks to also add their data
        \PoP\Root\App::getHookManager()->doAction(
            'PoP_Application_SectionUtils:dataloadqueryargs-latestcounts',
            array(&$ret)
        );
    }

    public static function addDataloadqueryargsAllcontent(&$ret)
    {
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if ($post_types = $cmsapplicationpostsapi->getAllcontentPostTypes()) {
            $ret['custompost-types'] = $post_types;
        }

        // Allow WordPress to add the 'tax-query' items
        \PoP\Root\App::getHookManager()->doAction(
            'PoP_Application_SectionUtils:dataloadqueryargs-allcontent',
            array(&$ret)
        );
    }

    public static function addDataloadqueryargsAllcontentBysingletag(&$ret)
    {
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $ret['tag-ids'] = [$tag_id];
        $ret['custompost-types'] = $cmsapplicationpostsapi->getAllcontentPostTypes();

        // Allow WordPress to add the 'tax-query' items
        \PoP\Root\App::getHookManager()->doAction(
            'PoP_Application_SectionUtils:dataloadqueryargs-allcontent-bysingletag',
            array(&$ret)
        );
    }
}
