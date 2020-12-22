<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Status: allow for arrays also
 */

HooksAPIFacade::getInstance()->addFilter('em_events_build_sql_conditions', 'gdEmEventsBuildSqlConditionsStatus', 10, 2);
function gdEmEventsBuildSqlConditionsStatus($conditions, $args)
{

    // Copied from plugins/events-manager/classes/em-events.php
    if (is_array($args['status'])) {
        $status_conditions = array();
        if (in_array('draft', $args['status'])) {
            $status_conditions[] = "`event_status` IS NULL"; //pending
        }
        if (in_array('pending', $args['status'])) {
            $status_conditions[] = "`event_status`=0"; //pending
        }
        if (in_array('publish', $args['status'])) {
            $status_conditions[] = "`event_status`=1"; //pending
        }

        if ($status_conditions) {
            $conditions['status'] = sprintf('(%s)', implode(' OR ', $status_conditions));
        }
    }

    return $conditions;
}


/**
 * Addition to post__not_in when searching events, to allow to exclude events
 * Otherwise not possible right now (http://wordpress.org/support/topic/exclude-current-event-from-listing)
 */
HooksAPIFacade::getInstance()->addFilter('em_events_get_default_search', 'gdEmEventsGetDefaultSearchExcludePostId', 10, 3);
function gdEmEventsGetDefaultSearchExcludePostId($defaults, $array, $super_defaults)
{
    if (!empty($array['post__not_in'])) {
        $defaults['post__not_in'] = $array['post__not_in'];
    }
    return $defaults;
}

HooksAPIFacade::getInstance()->addFilter('em_events_build_sql_conditions', 'gdEmEventsBuildSqlConditionsExcludePostId', 10, 2);
function gdEmEventsBuildSqlConditionsExcludePostId($conditions, $args)
{
    if (!empty($args['post__not_in'])) {
        if (is_array($args['post__not_in'])) {
            $conditions['post__not_in'] = "(".EM_EVENTS_TABLE.".post_id NOT IN (".implode(',', $args['post__not_in'])."))";
        } else {
            $conditions['post__not_in'] = "(".EM_EVENTS_TABLE.".post_id!={$args['post__not_in']})";
        }
    }

    return $conditions;
}
