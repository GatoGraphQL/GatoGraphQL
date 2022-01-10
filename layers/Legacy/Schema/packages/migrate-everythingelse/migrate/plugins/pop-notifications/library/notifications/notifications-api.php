<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

class PoP_Notifications_API
{
    protected static $additionalnotifications_sql;

    // public static function convertArgs($args) {

    //     // Convert the args from PoP Notifications to AAL format
    //     $keys = array(
    //         'action' => 'action',
    //         'objectType' => 'object_type',
    //         'objectSubtype' => 'object_subtype',
    //         'userID' => 'user_id',
    //         'objectID' => 'object_id',
    //         'objectName' => 'object_name',
    //     );
    //     $converted_args = array();
    //     foreach ($args as $key => $value) {

    //         $converted_args[$keys[$key]] = $value;
    //     }

    //     return $converted_args;
    // }
    // public static function convertArgs($args) {

    //     // Normalize the args
    //     // Make sure the $args only contain expected keys/values, filter out everything else
    //     // Props don't need to contain all the values below, though, not all of them are always needed
    //     return array_filter(
    //         $args,
    //         function($key) {
    //             return in_array(
    //                 $key,
    //                 array(
    //                     'action',
    //                     'object_type',
    //                     'object_subtype',
    //                     'user_id',
    //                     'object_id',
    //                     'object_name',
    //                 )
    //             );
    //         },
    //         ARRAY_FILTER_USE_KEY
    //     );
    // }

    public static function deleteLog($args)
    {

        // // Normalize the args
        // $args = self::convertArgs($args);

        // Delete a previous entry
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                'DELETE FROM `%1$s`
					WHERE `action` = %2$d
					AND `object_type` = %3$d
					AND `object_id` = %4$d',
                $wpdb->pop_notifications,
                $args['action'],
                $args['object_type'],
                $args['object_id']
            )
        );
    }

    public static function getNotifications($args, $actions = array())
    {

        // $actions: limit what actions will be executed
        // If empty, then no restriction
        // Needed for functions setStatus to collect all similar notifications to mark as read

        global $wpdb;
        $results = array();
        $cmsService = CMSServiceFacade::getInstance();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();

        // Merge with the defaults
        $args = array_merge(
            array(
                'include' => '',
                'array' => false,
                'fields' => '*',
                'user_id' => '',
                'hist_time' => ComponentModelComponentInfo::get('time'),
                'hist_time_compare' => '<',
                'order' => 'ASC',
                'orderby' => '',
                'limit' => $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')),
                'pagenumber' => 1,
                'joinstatus' => true,
                'status' => '',
                'user_id' => false,
            ),
            $args
        );
        extract($args);

        // $user_id: if none has been passed in the params, then check if the user is logged in, and use that
        if (!$user_id) {
            if (PoP_UserState_Utils::currentRouteRequiresUserState()) {
                $vars = ApplicationState::getVars();
                if (\PoP\Root\App::getState('is-user-logged-in')) {
                    $user_id = \PoP\Root\App::getState('current-user-id');
                }
            }
        }

        // Results output: object by default, array by param.
        $output = $array ? ARRAY_A : OBJECT;

        // Store the SQL WHERE statements, to be added by ORs
        $sql_where_user_ors = array();

        // Needed for the SQL
        $orderclause = '';
        $paginationclause = '';
        $joinstatusclause = '';
        $sql_where = '';

        // IDs already defined? Then just include them and that's it
        if ($include) {
            $sql_where = sprintf(
                '%s.histid in (%s)',
                $wpdb->pop_notifications,
                is_array($include) ? implode(', ', $include) : $include
            );
        } else {
            // We will build a list of OR conditions
            $sql_where_ors = array();

            //----------------------------------
            // General notifications
            //----------------------------------
            // Notify of a given post to all users: used with the Blog to make announcements of new releases, etc
            // It is an OR condition all on its own, because it doesn't depend on the user_registered time,
            // so we can show all the general notifications to the logged in user, even those created before the user registered
            $general_notification_actions = HooksAPIFacade::getInstance()->applyFilters(
                'AAL_PoP_API:notifications:general:actions',
                array(
                    AAL_POP_ACTION_POST_NOTIFIEDALLUSERS
                )
            );
            if ($actions) {
                $general_notification_actions = array_values(array_values(array_intersect($general_notification_actions, $actions)));
            }
            if ($general_notification_actions) {
                $sql_where_general_ands = array();
                $sql_where_general_ands[] = sprintf(
                    '
						%1$s.object_type = "Post"
					AND
						%1$s.action in (
							%2$s
						)
					',
                    $wpdb->pop_notifications,
                    arrayToQuotedString(
                        array(
                            AAL_POP_ACTION_POST_NOTIFIEDALLUSERS,
                        )
                    )
                );

                // Compare by hist_time?
                // hist_time: needed so we always query the notifications which happened before hist_time,
                // so that if there were new notification they don't get on the way, and fetching more will not bring again a same record
                if ($hist_time && $hist_time_compare) {
                    $sql_where_general_ands[] = sprintf(
                        '
							%1$s.hist_time %2$s %3$s
						',
                        $wpdb->pop_notifications,
                        $hist_time_compare,
                        $hist_time
                    );
                }

                // Add the OR statement for all general notifications
                $sql_where_ors[] = '('.implode(') AND (', $sql_where_general_ands).')';
            }

            //----------------------------------
            // User-specific notifications
            //----------------------------------
            if ($user_id) {
                // Below, create an array of AND statements for the given user

                //----------------------------------
                // Helper WHERE statements
                //----------------------------------

                // User is the object of the action
                $useristarget_where = sprintf(
                    '
						(
							%1$s.user_id != %2$s
						AND
							%1$s.object_id = %2$s
						)
					',
                    $wpdb->pop_notifications,
                    $user_id
                );

                // User-network: followed users + communities user is part of
                // Allow User Role Editor to hook into it, to add the Communities (GD_URE_METAKEY_PROFILE_COMMUNITIES)
                $user_plus_network_where = "1=1";
                if ($usernetwork_metakeys = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:usernetwork:metakeys',
                    array()
                )
                ) {
                    $usernetwork_keys = array();
                    foreach ($usernetwork_metakeys as $metakey) {
                        $usernetwork_keys[] = \PoPSchema\UserMeta\Utils::getMetaKey($metakey);
                    }

                    // $user_network_conditions: allow to hook more conditions into it
                    // Needed for User Role Editor to add Community Members from this user's communities
                    // ------------------------------
                    $user_network_conditions = array();
                    $user_network_conditions[] = sprintf(
                        '
							%2$s.object_id = %1$s
						',
                        $user_id,
                        $wpdb->pop_notifications
                    );
                    $user_network_conditions[] = sprintf(
                        '
							%4$s.user_id in (
								SELECT
									meta_value
								FROM
									%2$s
								WHERE
									(
										user_id = %1$s
									AND
										meta_key in (
											%3$s
										)
									)
							)
						',
                        $user_id,
                        $wpdb->usermeta,
                        arrayToQuotedString($usernetwork_keys),
                        $wpdb->pop_notifications
                    );
                    $user_network_conditions = HooksAPIFacade::getInstance()->applyFilters(
                        'AAL_PoP_API:notifications:usernetwork:conditions',
                        $user_network_conditions,
                        $user_id,
                        $args
                    );
                    $user_plus_network_where = sprintf(
                        '
							(
								%3$s.user_id != %1$s
							AND
								(
									%2$s
								)
							)
						',
                        $user_id,
                        '('.implode(') OR (', $user_network_conditions).')',
                        $wpdb->pop_notifications
                    );
                }



                // Posts which fulfil any of the following conditions:
                // - authored by the user,
                // - where the user has ever added a comment,
                // - where the user has been tagged (either post or comment),
                // - have a #hastag that the user is subscribed to,
                // but exclude the user him/herself (the user can recommend or post comments in his/her own posts)
                $useractivityposts_post_id_ors = array(
                    sprintf(
                        '
							post_author = %1$s
						',
                        $user_id
                    ),
                );
                $useractivityposts_post_id_unions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:useractivityposts:post_id_unions',
                    array(),
                    $user_id
                );
                if ($useractivityposts_post_id_unions) {
                    $useractivityposts_post_id_ors[] = sprintf(
                        '
							ID in (
									%1$s
							)
						',
                        implode(' UNION ', $useractivityposts_post_id_unions)
                    );
                }

                $useractivityposts_object_id_unions = array(
                    sprintf(
                        '
							SELECT
								ID
							FROM
								%1$s
							WHERE
									post_status = "publish"
								AND
									(
										%2$s
									)
						',
                        $wpdb->posts,
                        '('.implode(') OR (', $useractivityposts_post_id_ors).')'
                    ),
                );
                $useractivityposts_object_id_unions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:useractivityposts:object_id_unions',
                    $useractivityposts_object_id_unions,
                    $user_id
                );
                $useractivityposts_where = sprintf(
                    '
						(
							%2$s.user_id != %1$s
						AND
							%2$s.object_id in (
								%3$s
							)
						)
					',
                    $user_id,
                    $wpdb->pop_notifications,
                    implode(' UNION ', $useractivityposts_object_id_unions)
                );

                // Posts authored by the user, on any post_status, not just published
                $userposts_where = sprintf(
                    '
						(
							%3$s.user_id != %1$s
						AND
							%3$s.object_id in (
									SELECT
										ID
									FROM
										%2$s
									WHERE
										post_author = %1$s
							)
						)
					',
                    $user_id,
                    $wpdb->posts,
                    $wpdb->pop_notifications
                );

                //----------------------------------
                // Notifications
                //----------------------------------

                // User-specific Notifications:
                // - By Hook: "Welcome User" message
                // - By Hook: Twitter log-in: request user to update the (fake) email (WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL)
                $userspecific_notification_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:userspecific:actions',
                    array()
                );
                if ($actions) {
                    $userspecific_notification_actions = array_values(array_intersect($userspecific_notification_actions, $actions));
                }
                if ($userspecific_notification_actions) {
                    $sql_where_user_ors[] = sprintf(
                        '
							%3$s.object_type = "User"
						AND
							%3$s.action in (
								%1$s
							)
						AND
							%3$s.object_id = %2$s
						',
                        arrayToQuotedString($userspecific_notification_actions),
                        $user_id,
                        $wpdb->pop_notifications
                    );
                }

                // User actions: User + Network Notifications:
                // Notify the user when:
                // - By Hook: Someone from the network joins a community (URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY)
                // - By Hook: Anyone joined the user (user = community) (URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY)
                $userplusnetwork_notification_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:userplusnetwork-user:actions',
                    array()
                );
                if ($actions) {
                    $userplusnetwork_notification_actions = array_values(array_intersect($userplusnetwork_notification_actions, $actions));
                }
                if ($userplusnetwork_notification_actions) {
                    $sql_where_user_ors[] = sprintf(
                        '
							%3$s.object_type = "User"
						AND
							%3$s.action in (
								%1$s
							)
						AND
							%2$s
						',
                        arrayToQuotedString($userplusnetwork_notification_actions),
                        $user_plus_network_where,
                        $wpdb->pop_notifications
                    );
                }

                // Tags actions: User + Network Notifications:
                $userplusnetwork_notification_tag_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:userplusnetwork-tag:actions',
                    array()
                );
                if ($actions) {
                    $userplusnetwork_notification_tag_actions = array_values(array_intersect($userplusnetwork_notification_tag_actions, $actions));
                }
                if ($userplusnetwork_notification_tag_actions) {
                    $sql_where_user_ors[] = sprintf(
                        '
							%3$s.object_type = "Taxonomy"
						AND
							%3$s.object_subtype = "Tag"
						AND
							%3$s.action in (
								%1$s
							)
						AND
							%2$s
						',
                        arrayToQuotedString($userplusnetwork_notification_tag_actions),
                        $user_plus_network_where,
                        $wpdb->pop_notifications
                    );
                }

                // The User is the target of the action Notifications:
                // Notify the user when:
                // - By Hook: A community updates the membership of the user (URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP)
                $useristarget_notification_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:useristarget:actions',
                    array()
                );
                if ($actions) {
                    $useristarget_notification_actions = array_values(array_intersect($useristarget_notification_actions, $actions));
                }
                if ($useristarget_notification_actions) {
                    $sql_where_user_ors[] = sprintf(
                        '
							%3$s.object_type = "User"
						AND
							%3$s.action in (
								%1$s
							)
						AND
							%2$s
						',
                        arrayToQuotedString($useristarget_notification_actions),
                        $useristarget_where,
                        $wpdb->pop_notifications
                    );
                }

                // User Activity + Network Notifications:
                $useractivityposts_notification_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:useractivityposts:actions',
                    array()
                );
                if ($actions) {
                    $useractivityposts_notification_actions = array_values(array_intersect($useractivityposts_notification_actions, $actions));
                }
                if ($useractivityposts_notification_actions) {
                    $sql_where_user_ors[] = sprintf(
                        '
							%3$s.object_type = "Post"
						AND
							%3$s.action in (
								%1$s
							)
						AND
							%2$s
						',
                        arrayToQuotedString($useractivityposts_notification_actions),
                        $useractivityposts_where,
                        $wpdb->pop_notifications
                    );
                }

                // User Activity + Network Notifications:
                $useractivityplusnetwork_notification_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:notifications:useractivityplusnetwork:actions',
                    array()
                );
                if ($actions) {
                    $useractivityplusnetwork_notification_actions = array_values(array_intersect($useractivityplusnetwork_notification_actions, $actions));
                }
                if ($useractivityplusnetwork_notification_actions) {
                    // Make sure the post is published
                    $sql_where_user_ors[] = sprintf(
                        '
							%4$s.object_type = "Post"
						AND
							%4$s.action in (
								%1$s
							)
						AND
							(
								%2$s
							OR
								%3$s
							)
						AND
							%4$s.object_id in (
								SELECT
									ID
								FROM
									%5$s
								WHERE
									post_status = "publish"
							)
						',
                        arrayToQuotedString($useractivityplusnetwork_notification_actions),
                        $useractivityposts_where,
                        $user_plus_network_where,
                        $wpdb->pop_notifications,
                        $wpdb->posts
                    );
                }


                // Allow plug-ins to keep adding WHERE statements
                $sql_where_user_ors = HooksAPIFacade::getInstance()->applyFilters('PoP_Notifications_API:sql:wheres', $sql_where_user_ors, $args, $actions, $user_id, $userposts_where, $useractivityposts_where, $user_plus_network_where);

                $sql_where_user_ands = array();
                $sql_where_user_ands[] = '('.implode(') OR (', $sql_where_user_ors).')';

                // User registration date: show only notifications created after the user has registered
                // Otherwise, it will show the full activity history of his/her followed users, community members, etc. That's not cool
                if ($user_registered = $cmsusersapi->getUserRegistrationDate($user_id)) {
                    // user_registered date does not take into account the GMT, so add it again
                    $user_registered_time = strtotime($user_registered) + ($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:gmtOffset')) * 3600);

                    $sql_where_user_ands[] = sprintf(
                        '
							%1$s.hist_time >= %2$s
						',
                        $wpdb->pop_notifications,
                        $user_registered_time
                    );
                }

                // Compare by hist_time?
                // hist_time: needed so we always query the notifications which happened before hist_time,
                // so that if there were new notification they don't get on the way, and fetching more will not bring again a same record
                if ($hist_time && $hist_time_compare) {
                    $sql_where_user_ands[] = sprintf(
                        '
							%1$s.hist_time %2$s %3$s
						',
                        $wpdb->pop_notifications,
                        $hist_time_compare,
                        $hist_time
                    );
                }

                // Allow plug-ins to keep adding WHERE statements
                $sql_where_user_ands = HooksAPIFacade::getInstance()->applyFilters('AAL_PoP_API:sql:where_ands', $sql_where_user_ands, $args);

                $sql_where_ors[] = '('.implode(') AND (', $sql_where_user_ands).')';
            }

            // Finally, merge all the ORs into the where statement
            $sql_where = '('.implode(') OR (', $sql_where_ors).')';

            if ($orderby) {
                $orderclause = sprintf(
                    '
						ORDER BY
							%s %s
					',
                    $orderby,
                    $order ? $order : 'ASC'
                );
            }

            // Use pagination if a limit was set
            if ($limit > 0) {
                $paginationclause = sprintf(
                    '
                        LIMIT
                            %s
                    ',
                    $limit
                );
                if ($pagenumber >= 2) {
                    $offset = ($pagenumber - 1) * $limit;
                    $paginationclause .= sprintf(
                        '
    						OFFSET
    							%s
    					',
                        $offset
                    );
                }
            }
        }

        // Join the Log table with the Status table, on colums histid and user_id
        if ($user_id && $joinstatus) {
            if ($fields == '*') {
                $fields = sprintf(
                    '%s.*, %s.*',
                    $wpdb->pop_notifications,
                    $wpdb->pop_notifications_status
                );
            }

            // Filter by status value?
            if ($status) {
                if (strtolower($status) == 'null') {
                    $statuswhereclause = sprintf(
                        '
							%1$s.status is null
						',
                        $wpdb->pop_notifications_status
                    );
                } else {
                    $statuswhereclause = sprintf(
                        '
							%1$s.status = "%2$s"
						',
                        $wpdb->pop_notifications_status,
                        $status
                    );
                }
                $sql_where = sprintf(
                    '(%s) AND %s',
                    $sql_where,
                    $statuswhereclause
                );
            }
            // else {
            //     $status_value = '1=1';
            // }
            $joinstatusclause = sprintf(
                '
					LEFT OUTER JOIN
						`%2$s`
					ON
						(
							%2$s.status_histid = %1$s.histid
						AND
							%2$s.status_user_id = %3$s
						)
				',
                $wpdb->pop_notifications,
                $wpdb->pop_notifications_status,
                $user_id
            );
        }

        $sql = sprintf(//$wpdb->prepare(
            '
				SELECT
					%s
				FROM
					`%s`
				%s
				WHERE
					%s
				%s
				%s
				;
			',
            is_array($fields) ? implode(', ', $fields) : $fields,
            $wpdb->pop_notifications,
            $joinstatusclause,
            $sql_where,
            $orderclause,
            $paginationclause
        );
        $results = $wpdb->get_results($sql, $output);

        return $results;
    }

    public static function getNotification($histid)
    {
        global $wpdb;
        $query = array(
            'include'        => array($histid),
            'joinstatus'    => false,
        );

        $results = self::getNotifications($query);

        if ($results) {
            return $results[0];
        }

        return null;
    }

    public static function deletePostNotifications($user_id, $post_id, $actions)
    {
        self::deleteObjectNotifications($user_id, "Post", $post_id, $actions);
    }

    public static function deleteUserNotifications($user_id, $userobject_id, $actions)
    {
        self::deleteObjectNotifications($user_id, "User", $userobject_id, $actions);
    }

    protected static function deleteObjectNotifications($user_id, $object_type, $object_id, $actions)
    {

        // Function called when the admin_approval needs to delete previous notifications about the post state
        global $wpdb;

        // Find the $histid to delete, since they'll have to be deleted in 2 tables: activity_log and activity_log_status
        $sql = sprintf(
            '
			SELECT
				histid
			FROM
				`%1$s`
			WHERE
				(
					`user_id` = %2$d
				AND
					`object_type` = "%3$s"
				AND
					`object_id` = %4$d
				AND
					`action` in (%5$s)
				)
			',
            $wpdb->pop_notifications,
            $user_id,
            $object_type,
            $object_id,
            arrayToQuotedString($actions)
        );
        if ($results = $wpdb->get_results($sql)) {
            $histids = array();
            foreach ($results as $result) {
                $histids[] = $result->histid;
            }

            self::deleteNotifications($histids);
        }
    }

    public static function clearUser($user_id)
    {

        // Function called when a user is deleted from the system
        global $wpdb;

        // Delete the activity log: either done by the user, or where the user is the target
        // Prepare the WHERE OR statements to delete the status
        $status_sql_where_ors = array();
        $status_sql_where_ors[] = sprintf(
            '
				`status_user_id` = %1$d
			',
            $user_id
        );

        // Find the $histid to delete, since they'll have to be deleted in 2 tables: activity_log and activity_log_status
        $sql = $wpdb->prepare(
            '
			SELECT
				histid
			FROM
				`%1$s`
			WHERE
				(
					`user_id` = %2$d
				OR
					(
						`object_type` = "User"
					AND
						`object_id` = %2$d
					)
				)
			',
            $wpdb->pop_notifications,
            $user_id
        );
        if ($results = $wpdb->get_results($sql)) {
            $histids = array();
            foreach ($results as $result) {
                $histids[] = $result->histid;
            }

            // Delete all records from the activity log
            $wpdb->query(
                sprintf(
                    '
					DELETE FROM
						`%1$s`
					WHERE
						`histid` in (%2$s)
					',
                    $wpdb->pop_notifications,
                    implode(',', $histids)
                )
            );

            // Add the corresponding records to be delete from the status table
            $status_sql_where_ors[] = sprintf(
                '
					`status_histid` in (%1$s)
				',
                implode(',', $histids)
            );
        }

        // Delete the status for that user, plus the status for all other users involving the deleted user
        $wpdb->query(
            sprintf(
                '
				DELETE FROM
					`%1$s`
				WHERE
					%2$s
				',
                $wpdb->pop_notifications_status,
                '('.implode(') OR (', $status_sql_where_ors).')'
            )
        );
    }

    protected static function deleteNotifications($histids)
    {
        global $wpdb;

        $joined_histids = implode(',', $histids);

        // Delete all records from the activity log
        $wpdb->query(
            sprintf(
                '
				DELETE FROM
					`%1$s`
				WHERE
					`histid` in (%2$s)
				',
                $wpdb->pop_notifications,
                $joined_histids
            )
        );

        // Delete the status involving the deleted comment for all users
        $wpdb->query(
            sprintf(
                '
				DELETE FROM
					`%1$s`
				WHERE
					`status_histid` in (%2$s)
				',
                $wpdb->pop_notifications_status,
                $joined_histids
            )
        );
    }

    public static function setStatus($histid, $user_id, $status = null)
    {
        global $wpdb;

        $ret = array($histid);

        // First delete the current record from the DB
        $wpdb->query(
            $wpdb->prepare(
                '
				DELETE FROM
					`%1$s`
				WHERE
					(
						`status_histid` = %2$d
					AND
						`status_user_id` = %3$d
					)
				',
                $wpdb->pop_notifications_status,
                $histid,
                $user_id
            )
        );

        // If status is null => mark as unread => already done
        // If not null, then insert it in the DB
        if ($status) {
            $wpdb->insert(
                $wpdb->pop_notifications_status,
                array(
                    'status_histid'        => $histid,
                    'status_user_id'       => $user_id,
                    'status'             => $status,
                ),
                array('%d', '%d', '%s')
            );

            // Set the status for all additional (similar) notifications
            $additional = self::setStatusMultipleNotifications($user_id, $status, $histid);
            $ret = array_merge(
                $ret,
                $additional
            );
        }

        return $ret;
    }

    public static function setStatusMultipleNotifications($user_id, $status = AAL_POP_STATUS_READ, $histid = null)
    {
        global $wpdb;

        // --------------------------------------------------
        // Set status for additional notifications
        // --------------------------------------------------
        // Used with
        // 1. Mark all as read ($histid = null)
        // 2. Additional (similar) notifications ($histid not null):
        // Mark as read also all the similar notifications. Eg: when adding 3 comments on the same post,
        // then it will generate 3 notifications, all pointing to the same link. Clicking on 1 notification,
        // the user will have seen all updates. So we can safely assume that they can all be marked as read
        // --------------------------------------------------
        $actions = array();
        if ($histid) {
            // 2. Additional (similar) notifications ($histid not null)
            $notification = self::getNotification($histid);
            $objectids_sql = '';
            if ($notification->object_type == "Post") {
                $post_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:additional_notifications:markasread:posts:actions',
                    array()
                );
                if (in_array($notification->action, $post_actions)) {
                    $actions = $post_actions;
                    $objectids_sql = sprintf(
                        '
							%1$s.object_id = %2$s
						',
                        $wpdb->pop_notifications,
                        $notification->object_id
                    );
                }
            } elseif ($notification->object_type == "User") {
                $user_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:additional_notifications:markasread:users:actions',
                    array()
                );
                $sameuser_actions = HooksAPIFacade::getInstance()->applyFilters(
                    'AAL_PoP_API:additional_notifications:markasread:sameusers:actions',
                    array()
                );
                if (in_array($notification->action, $user_actions)) {
                    $actions = $user_actions;
                    $objectids_sql = sprintf(
                        '
							%1$s.object_id = %2$s
						',
                        $wpdb->pop_notifications,
                        $notification->object_id
                    );
                } elseif (in_array($notification->action, $sameuser_actions)) {
                    $actions = $sameuser_actions;
                    $objectids_sql = sprintf(
                        '
							%1$s.object_id = %2$s
						AND
							%1$s.user_id = %3$s
						',
                        $wpdb->pop_notifications,
                        $notification->object_id,
                        $notification->user_id
                    );
                }
            } elseif ($notification->object_type == "Taxonomy") {
                if ($notification->object_subtype == "Tag") {
                    $tag_actions = HooksAPIFacade::getInstance()->applyFilters(
                        'AAL_PoP_API:additional_notifications:markasread:tags:actions',
                        array()
                    );
                    if (in_array($notification->action, $tag_actions)) {
                        $actions = $tag_actions;
                        $objectids_sql = sprintf(
                            '
								%1$s.object_id = %2$s
							',
                            $wpdb->pop_notifications,
                            $notification->object_id
                        );
                    }
                }
            }

            // Allow plug-ins to add their own logic
            $multiple_status_data = array($objectids_sql, $actions);
            $multiple_status_data = HooksAPIFacade::getInstance()->applyFilters('PoP_Notifications_API:multiple-status:objectids-sql', $multiple_status_data, $notification);
            $objectids_sql = $multiple_status_data[0];
            $actions = $multiple_status_data[1];

            if ($objectids_sql) {
                // Generate the SQL and pass it to the filter
                // Comment Leo 10/03/2016: there should be no need to ask for %1$s.action = $notification->user_id in this SQL,
                // since going to the single post the user will see all comments, from all users.
                // Problem is, the user will not get notifications from everyone, so it's wrong to get all notification from all user_id for the same object_id,
                // So we keep it simple and conservative, and we also check for the same user_id
                self::$additionalnotifications_sql = sprintf(
                    '
						(
								%1$s.histid != %2$s
							AND
								%1$s.action = "%3$s"
							AND
								%1$s.object_type = "%4$s"
							AND
								%5$s
						)
					',
                    $wpdb->pop_notifications,
                    $histid,
                    $notification->action,
                    $notification->object_type,
                    $objectids_sql
                );
            } else {
                // If it is none of these cases, then do nothing
                return array();
            }
        }

        // Get the notifications, limiting the results for the additional notifications,
        // or not limiting for "mark all as read"
        if (self::$additionalnotifications_sql) {
            // Add a filter to add an extra AND statement, remove it immediately after
            HooksAPIFacade::getInstance()->addFilter(
                'AAL_PoP_API:sql:where_ands',
                array(PoP_Notifications_API::class, 'addAdditionalnotificationsStatusSql'),
                10,
                2
            );
        }
        $args = array(
            // Bring all the results
            'limit' => -1,
            // Only bring notifications which are not read already
            'status' => 'null',
            // Only the IDs are needed
            'fields' => array(
                sprintf('%s.histid', $wpdb->pop_notifications),
                // sprintf('%s.status', $wpdb->pop_notifications_status)
            ),
            'user_id' => $user_id,
        );

        $results = self::getNotifications($args, $actions);
        if (self::$additionalnotifications_sql) {
            HooksAPIFacade::getInstance()->removeFilter(
                'AAL_PoP_API:sql:where_ands',
                array(PoP_Notifications_API::class, 'addAdditionalnotificationsStatusSql'),
                10,
                2
            );
        }

        // Mark as read all the results
        $ret = array();
        if ($results) {
            foreach ($results as $result) {
                // Only if not read already
                // if ($result->status != $status) {

                $wpdb->insert(
                    $wpdb->pop_notifications_status,
                    array(
                        'status_histid'        => $result->histid,
                        'status_user_id'       => $user_id,
                        'status'             => $status,
                    ),
                    array('%d', '%d', '%s')
                );

                // Add to the results, to print the styles also for these notifications
                $ret[] = $result->histid;
                // }
            }
        }

        return $ret;
    }

    public static function addAdditionalnotificationsStatusSql($sql_where_user_ands, $args)
    {
        $sql_where_user_ands[] = self::$additionalnotifications_sql;
        return $sql_where_user_ands;
    }
}
