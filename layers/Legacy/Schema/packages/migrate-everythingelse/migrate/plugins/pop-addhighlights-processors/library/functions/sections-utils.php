<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_AddHighlights_Module_Processor_SectionBlocksUtils
{
    public static function addDataloadqueryargsSinglehighlights(&$ret, $post_id = null)
    {
        if (is_null($post_id)) {
            $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        }

        // $ret['custompost-types'] = [POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT];

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_HIGHLIGHTEDPOST),
            'value' => $post_id,
        ];
    }
}
