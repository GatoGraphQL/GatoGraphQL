<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * Add Co-authors to Events Manager
 */
function gdUserEventPostIds($authors = null)
{

    // Allow to specify for which users (used by Events Map profile filtering)
    if (is_null($authors)) {
        $vars = ApplicationState::getVars();
        $authors = $vars['global-userstate']['current-user-id'];
    }

    $post_ids = array();
    $user_args = array(
        'nopaging' => true,
        'post_type' => EM_POST_TYPE_EVENT,
        'author' => is_array($authors) ? implode(',', $authors) : $authors,
        'post_status' => array('draft', 'future', 'pending', 'publish')
    );
    $user_posts = new WP_Query($user_args);
    while ($user_posts->have_posts()) :
        $user_posts->next_post();
        $post_ids[] = $user_posts->post->ID;
    endwhile;

    return $post_ids;
}

// HooksAPIFacade::getInstance()->addFilter('EventTypeDataLoader:query', 'gdCapEmDataloaderChangeOwnerWithPostIds', 10);
// function gdCapEmDataloaderChangeOwnerWithPostIds($query)
// {

//     // Always use no owner. If needed to filter by author, it's done getting the post_ids below (because of interaction with CoAuthor Plus plugin)
//     if ($profiles = $query['owner'] ?? null) {
//         $query['owner'] = false;

//         $post_ids = gdUserEventPostIds($profiles);

//         // If empty, then force it to bring no results with a -1 id (otherwise, it brings all results)
//         $post_ids = empty($post_ids) ? array(-1) : $post_ids;

//         // Limit posts to the profile
//         $query['post_id'] = $post_ids;
//     }

//     return $query;
// }
HooksAPIFacade::getInstance()->addFilter('EM_PoP_Events_API:get:query', 'gdCapEmFilterChangeOwnerWithPostIds');
function gdCapEmFilterChangeOwnerWithPostIds($query)
{

    // Remove the author, use owner post_id instead
    if ($profiles = $query['owner'] ?? null) {
        $post_ids = gdUserEventPostIds($profiles);

        // If empty, then force it to bring no results with a -1 id (otherwise, it brings all results)
        if (empty($post_ids)) {
            $post_ids = array(-1);
        }
        // Limit posts to the profile
        $query['post_id'] = $post_ids;
        $query['owner'] = false;
    }

    return $query;
}

/**
 * Override can_manage allowing co-authors
 */

// Override the can_manage function from em-event and em-object: this one contrasts against owner (which is unique) instead of (multiple) authors, so it does not co-authors to edit the event
function gdEmCapCanManage($event, $owner_capability = false, $admin_capability = false, $user_to_check = false)
{

    // copied from events-manager/classes/em-object.php
    // Then replaced `owner` with `author`
    global $em_capabilities_array;
    if ($user_to_check) {
        $user = new WP_User($user_to_check);
        if (empty($user->ID)) {
            $user = false;
        }
    }

    $vars = ApplicationState::getVars();
    $pluginapi = PoP_Coauthors_APIFactory::getInstance();

    $authors = $pluginapi->getCoauthors($event->post_id, ['return-type' => ReturnTypes::IDS]);
    $is_author = ((in_array($vars['global-userstate']['current-user-id'], $authors)) || (!empty($user) && in_array($user->ID, $authors)));

    //now check capability
    $can_manage = false;
    if ($is_author && (current_user_can($owner_capability) || (!empty($user) && $user->has_cap($owner_capability)))) {
        //user owns the object and can therefore manage it
        $can_manage = true;
    }

    return $can_manage;
}

// Override the can_manage function from em-event and em-object: this one contrasts against owner (which is unique) instead of (multiple) authors, so it does not co-authors to edit the event
HooksAPIFacade::getInstance()->addFilter('em_event_can_manage', 'gdEmCapEventCanManage', 10, 5);
function gdEmCapEventCanManage($can_manage, $event, $owner_capability = false, $admin_capability = false, $user_to_check = false)
{
    if ($can_manage) {
        return true;
    }

    return gdEmCapCanManage($event, $owner_capability, $admin_capability, $user_to_check);
}

HooksAPIFacade::getInstance()->addFilter('gdEmCanManageAddError', 'gdEmCanManageAddError', 10, 5);
function gdEmCanManageAddError($add_error, $event, $owner_capability = false, $admin_capability = false, $user_to_check = false)
{
    if (!$add_error) {
        return false;
    }

    return !gdEmCapCanManage($event, $owner_capability, $admin_capability, $user_to_check);
}


/**
 * Include in the list of My Events also the co-authors events
 */

HooksAPIFacade::getInstance()->addFilter('gd_em_events_admin_args', 'gdEmEventsAdminArgsAddCoauthors', 10, 2);
HooksAPIFacade::getInstance()->addFilter('gd_em_events_admin_pending_count_args', 'gdEmEventsAdminArgsAddCoauthors', 10, 2);
HooksAPIFacade::getInstance()->addFilter('gd_em_events_admin_draft_count_args', 'gdEmEventsAdminArgsAddCoauthors', 10, 2);
HooksAPIFacade::getInstance()->addFilter('gd_em_events_admin_past_count_args', 'gdEmEventsAdminArgsAddCoauthors', 10, 2);
HooksAPIFacade::getInstance()->addFilter('gd_em_events_admin_future_count_args', 'gdEmEventsAdminArgsAddCoauthors', 10, 2);
function gdEmEventsAdminArgsAddCoauthors($args, $event)
{
    $post_ids = gdUserEventPostIds();
    if (!empty($post_ids)) {
        $args['owner'] = false;
        $args['post_id'] = $post_ids;
    }

    return $args;
}

HooksAPIFacade::getInstance()->addFilter('gd_em_bookings_events_table_args', 'gdEmGetBookingsLimitToCoauthoredEvents');
HooksAPIFacade::getInstance()->addFilter('gd_em_get_bookings_person_args', 'gdEmGetBookingsLimitToCoauthoredEvents');
function gdEmGetBookingsLimitToCoauthoredEvents($args)
{

    // Limit which events they can see: the ones they have access to through co-authoring
    $post_ids = gdUserEventPostIds();

    if (!empty($post_ids)) {
        // Convert the list of post_ids to event_ids
        $event_ids = array();
        foreach ($post_ids as $post_id) {
            $event = em_get_event($post_id, 'post_id');
            $event_ids[] = $event->event_id;
        }
        $args['event'] = $event_ids;
    }

    return $args;
}

HooksAPIFacade::getInstance()->addFilter('gd_em_bookings_events_table_args', 'gdEmGetBookingsNoOwnerArgs');
HooksAPIFacade::getInstance()->addFilter('gd_em_get_bookings_person_args', 'gdEmGetBookingsNoOwnerArgs');
HooksAPIFacade::getInstance()->addFilter('gd_em_get_bookings_event_args', 'gdEmGetBookingsNoOwnerArgs');
HooksAPIFacade::getInstance()->addFilter('gd_em_get_bookings_args', 'gdEmGetBookingsNoOwnerArgs');
function gdEmGetBookingsNoOwnerArgs($args)
{

    // Allow for co-authors: owner = false => the profiles can see also events which are owned by others
    $args['owner'] = false;

    return $args;
}

/**
 * When doing a .csv export, there is a bug from EM, so we need to get the person_id from the request and put it in the args to filter by person
 * Priority 20: do it after the other hooks, to give them the chance to execute first
 */
HooksAPIFacade::getInstance()->addFilter('gd_em_get_bookings_args', 'gdEmGetBookingsCsvPersonArgs', 20);
function gdEmGetBookingsCsvPersonArgs($args)
{
    if (!empty($_REQUEST['person_id'] ?? '')) {
        $args['person'] = $_REQUEST['person_id'];
    }

    // Check if the limiting by event was done, if not do it
    if (!isset($args['event'])) {
        $args = gdEmGetBookingsLimitToCoauthoredEvents($args);
    }

    if (!empty($_REQUEST['event_id'] ?? '') && !isset($args['event'])) {
        $args['event'] = $_REQUEST['event_id'];
    }

    return $args;
}


// Allow other profiles to see co-authors bookings
HooksAPIFacade::getInstance()->addFilter('em_bookings_build_sql_conditions', 'gdEmBookingsBuildSqlConditions', 10, 2);
function gdEmBookingsBuildSqlConditions($conditions, $args)
{

    // copied from function build_sql_conditions in em-bookings.php

    // Restrain the following:
    // 1. Person: only the person from the link
    if (is_numeric($args['person'])) {
        $conditions['person'] = EM_BOOKINGS_TABLE.'.person_id='.$args['person'];
    }

    return $conditions;
}
