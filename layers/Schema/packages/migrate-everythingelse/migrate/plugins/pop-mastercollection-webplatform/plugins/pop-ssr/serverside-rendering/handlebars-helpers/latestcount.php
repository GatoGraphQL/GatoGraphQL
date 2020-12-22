<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_LatestCountHelpers
{
    public function latestCountTargets($dbObject, $options)
    {

        // Build the notification prompt targets, based on the data on the dbObject
        $targets = array();
        $selector = '.pop-block.'.GD_JS_INITIALIZED.' > .blocksection-latestcount > .pop-latestcount';

        // By Sections (post type . categories)
        $trigger_values = $dbObject['latestcountsTriggerValues'] ?? array();
        foreach ($trigger_values as $trigger_value) {
            // trigger_value will be translated to 'postType'+'mainCategory' attribute
            $targets[] = $selector.'.'.$trigger_value;
        }

        // By Tags
        foreach ($dbObject['tags'] as $tag) {
            $target = $selector.'.tag'.$tag;
            $targets[] = $target;
        }

        // By author pages
        foreach ($dbObject['authors'] as $author) {
            $target = $selector.'.author'.$author;

            // ... combined with Categories
            if ($trigger_values) {
                foreach ($trigger_values as $trigger_value) {
                    $targets[] = $target.'.author-'.$trigger_value;
                }
            } else {
                $targets[] = $target;
            }
        }

        // By single relatedto posts
        foreach ($dbObject['references'] as $post_id) {
            $target = $selector.'.single'.$post_id;

            // ... combined with Categories
            if ($trigger_values) {
                foreach ($trigger_values as $trigger_value) {
                    $targets[] = $target.'.single-'.$trigger_value;
                }
            } else {
                $targets[] = $target;
            }
        }

        return new LS(implode(',', $targets));
    }
}

/**
 * Initialization
 */
global $pop_serverside_latestcounthelpers;
$pop_serverside_latestcounthelpers = new PoP_ServerSide_LatestCountHelpers();
