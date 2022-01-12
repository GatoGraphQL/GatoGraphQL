<?php

use PoP\ComponentModel\State\ApplicationState;

class UserStance_Module_Processor_CustomSectionBlocksUtils
{
    public static function addDataloadqueryargsStancesaboutpost(&$ret, $referenced_post_id)
    {
        $ret['meta-query'][] = [
            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_STANCETARGET),
            'value' => $referenced_post_id,
        ];
        $ret['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];
        // $ret['fields'] = 'ids';
    }

    public static function addDataloadqueryargsGeneralstances(&$ret)
    {
        $ret['meta-query'][] = [
            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_STANCETARGET),
            'compare' => 'NOT EXISTS',
        ];
        $ret['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];
        // $ret['fields'] = 'ids';
    }

    public static function addDataloadqueryargsPoststances(&$ret)
    {

        // All results where there is an article involved
        $ret['meta-query'][] = [
            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_STANCETARGET),
            'compare' => 'EXISTS',
        ];
        $ret['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];
        // $ret['fields'] = 'ids';
    }

    public static function addDataloadqueryargsSinglestances(&$ret, $referenced_post_id = null)
    {
        if (!$referenced_post_id) {
            $referenced_post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        }

        $ret['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_STANCETARGET),
            'value' => $referenced_post_id,
        ];
    }
}
