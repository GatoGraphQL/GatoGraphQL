<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_PoP_API extends AAL_API {

	protected $additionalnotifications_sql;

	// Override original function, so that it does nothing here
	protected function _delete_old_items() {
	}

	// Override original function, so that it does nothing here
	public function erase_all_items() {
	}

	public function get_notifications($args, $actions = array()) {

		// $actions: limit what actions will be executed
		// If empty, then no restriction
		// Needed for functions set_status to collect all similar notifications to mark as read

		global $wpdb;
		$results = array();

		$args = wp_parse_args(
			$args,
			array(
				'include'         	=> '',
				'array'    			=> false,
				'fields' 			=> '*',
				'user_id'    		=> '',
				'hist_time'    		=> POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/,
				'hist_time_compare'	=> '<',
				'order'				=> 'ASC',
				'orderby'			=> '',
				'limit'				=> get_option('posts_per_page'),
				'paged'				=> 1,
				'joinstatus'		=> true,
				'status'			=> '',
			)
		);
		extract($args);

		// $user_id: if none has been passed in the params, then check if the user is logged in, and use that
		if (!$user_id && is_user_logged_in()) {
			$user_id = get_current_user_id();
		}

		// Results output: object by default, array by param.
		$output = $array ? ARRAY_A : OBJECT;

		// Store the SQL WHERE statements, to be added by ORs
		$sql_where_user_ors = array();

		$paginate = false;

		// Needed for the SQL
		$orderclause = '';
		$paginationclause = '';
		$joinstatusclause = '';
		$sql_where = '';

		// IDs already defined? Then just include them and that's it
		if ($include) {

			$sql_where = sprintf(
				'%s.histid in (%s)',
				$wpdb->activity_log,
				is_array($include) ? implode(', ', $include) : $include
			);
		}
		else {

			// We will build a list of OR conditions
			$sql_where_ors = array();

			//----------------------------------
			// General notifications
			//----------------------------------
			// Notify of a given post to all users: used with the Blog to make announcements of new releases, etc
			// It is an OR condition all on its own, because it doesn't depend on the user_registered time,
			// so we can show all the general notifications to the logged in user, even those created before the user registered
			$general_notification_actions = apply_filters(
				'AAL_PoP_API:notifications:general:actions',
				array(
					AAL_POP_ACTION_POST_NOTIFIEDALLUSERS
				)
			);
			if ($actions) {
				$general_notification_actions = array_intersect($general_notification_actions, $actions);
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
					$wpdb->activity_log,
					array_to_quoted_string(array(
						AAL_POP_ACTION_POST_NOTIFIEDALLUSERS,
					))
				);

				// Compare by hist_time?
				// hist_time: needed so we always query the notifications which happened before hist_time,
				// so that if there were new notification they don't get on the way, and fetching more will not bring again a same record
				if ($hist_time && $hist_time_compare) {
					$sql_where_general_ands[] = sprintf(
						'
							%1$s.hist_time %2$s %3$s
						',
						$wpdb->activity_log,
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
					$wpdb->activity_log,
					$user_id
				);

				// Posts which have a #hastag that the user is subscribed to
				$posthashashtagtheuserissubscribedto_where = sprintf(
					'
						SELECT 
							object_id 
						FROM 
							%2$s
						INNER JOIN
							%3$s
						ON
							%2$s.term_taxonomy_id = %3$s.term_taxonomy_id
						WHERE
							%3$s.term_id in (
								SELECT 
									meta_value 
								FROM 
									%4$s
								WHERE
										user_id = %1$s
									AND
										meta_key = "%5$s"
							)
					',
					$user_id,
					$wpdb->term_relationships,
					$wpdb->term_taxonomy,
					$wpdb->usermeta,
					GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS, GD_META_TYPE_USER)
				);

				// User-network: followed users + communities user is part of
				// Allow User Role Editor to hook into it, to add the Communities (GD_URE_METAKEY_PROFILE_COMMUNITIES)
				$user_plus_network_where = "1=1";
				if ($usernetwork_metakeys = apply_filters(
					'AAL_PoP_API:notifications:usernetwork:metakeys',
					array(
						GD_METAKEY_PROFILE_FOLLOWSUSERS,
					)
				)) {
					$usernetwork_keys = array();
					foreach ($usernetwork_metakeys as $metakey) {
						$usernetwork_keys[] = GD_MetaManager::get_meta_key($metakey, GD_META_TYPE_USER);
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
						$wpdb->activity_log
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
						array_to_quoted_string($usernetwork_keys),
						$wpdb->activity_log
					);
					$user_network_conditions = apply_filters(
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
						$wpdb->activity_log
					);
				}

				// Posts which fulfil any of the following conditions:
				// - authored by the user, 
				// - where the user has ever added a comment, 
				// - where the user has been tagged (either post or comment), 
				// - have a #hastag that the user is subscribed to, 
				// but exclude the user him/herself (the user can recommend or post comments in his/her own posts)
				$useractivityposts_where = sprintf(
					'
						(
							%4$s.user_id != %1$s
						AND
							%4$s.object_id in (
									SELECT 
										ID 
									FROM 
										%2$s
									WHERE
											post_status = "publish"
										AND
											(
												post_author = %1$s
											OR
												ID in (
														SELECT 
															post_id 
														FROM 
															%5$s
														WHERE
																meta_key = "%6$s"
															AND
																meta_value = %1$s
													UNION
														%9$s
												)
											)
											
								UNION
									SELECT 
										comment_post_ID 
									FROM 
										%3$s
									INNER JOIN
										%2$s
									ON 
										%3$s.comment_post_ID = %2$s.ID
									WHERE
										(
											%3$s.comment_approved = "1"
										AND
											%2$s.post_status = "publish"
										AND
											(
												%3$s.user_id = %1$s
											OR
												comment_ID in (
													SELECT 
														comment_id 
													FROM 
														%7$s
													WHERE
															meta_key = "%8$s"
														AND
															meta_value = %1$s
												)
											)
										)
							)
						)
					',
					$user_id,
					$wpdb->posts,
					$wpdb->comments,
					$wpdb->activity_log,
					$wpdb->postmeta,
					GD_MetaManager::get_meta_key(GD_METAKEY_POST_TAGGEDUSERS, GD_META_TYPE_POST),
					$wpdb->commentmeta,
					GD_MetaManager::get_meta_key(GD_METAKEY_COMMENT_TAGGEDUSERS, GD_META_TYPE_COMMENT),
					$posthashashtagtheuserissubscribedto_where
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
					$wpdb->activity_log
				);

				// Comments:
				// - from someone else replying to a comment by the user,
				// posting a comment in a post by the user, 
				// comment tagging the user, 
				// comment in a post where the user is tagged,
				// comment in a post with a #hashtag the user is subscribed to
				$useractivitycomments_where = sprintf(
					'
						(
							%4$s.user_id != %1$s
						AND
							%4$s.object_id in (
									SELECT 
										comment_ID 
									FROM 
										%2$s
									WHERE
										(
											(
												comment_parent in (
													SELECT 
														comment_ID 
													FROM 
														%2$s
													WHERE
														user_id = %1$s
												)
											OR
												comment_post_ID in (
													(
														SELECT 
															ID 
														FROM 
															%3$s
														WHERE
															(
																post_author = %1$s
															AND
																post_status = "publish"
															)
													UNION
														SELECT 
															post_id 
														FROM 
															%5$s
														WHERE
																meta_key = "%6$s"
															AND
																meta_value = %1$s
													UNION
														%9$s
													)
												)
											OR
												comment_ID in (
													SELECT 
														comment_id 
													FROM 
														%7$s
													WHERE
															meta_key = "%8$s"
														AND
															meta_value = %1$s
												)
											)
										AND
											comment_approved = "1"
										)
							)
						)
					',
					$user_id,
					$wpdb->comments,
					$wpdb->posts,
					$wpdb->activity_log,
					$wpdb->postmeta,
					GD_MetaManager::get_meta_key(GD_METAKEY_COMMENT_TAGGEDUSERS, GD_META_TYPE_POST),
					$wpdb->commentmeta,
					GD_MetaManager::get_meta_key(GD_METAKEY_COMMENT_TAGGEDUSERS, GD_META_TYPE_COMMENT),
					$posthashashtagtheuserissubscribedto_where
				);

				// Comments added by the user
				$usercomments_where = sprintf(
					'
						%3$s.object_id in (
							SELECT 
								comment_ID 
							FROM 
								%2$s
							WHERE
								user_id = %1$s
						)
					',
					$user_id,
					$wpdb->comments,
					$wpdb->activity_log
				);

				//----------------------------------
				// Notifications
				//----------------------------------

				// User-specific Notifications:
				// - "Welcome User" message
				// - By Hook: Twitter log-in: request user to update the (fake) email (WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL)
				$userspecific_notification_actions = apply_filters(
					'AAL_PoP_API:notifications:userspecific:actions',
					array(
						AAL_POP_ACTION_USER_WELCOMENEWUSER,
					)
				);
				if ($actions) {
					$userspecific_notification_actions = array_intersect($userspecific_notification_actions, $actions);
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
						array_to_quoted_string($userspecific_notification_actions),
						$user_id,
						$wpdb->activity_log
					);
				}

				// User actions: User + Network Notifications:
				// Notify the user when:
				// - Someone from the network follows a user
				// - By Hook: Someone from the network joins a community (URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY)
				// - Anyone follows the user
				// - By Hook: Anyone joined the user (user = community) (URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY)
				$userplusnetwork_notification_actions = apply_filters(
					'AAL_PoP_API:notifications:userplusnetwork-user:actions',
					array(
						AAL_POP_ACTION_USER_FOLLOWSUSER,
						AAL_POP_ACTION_USER_UNFOLLOWSUSER,
					)
				);
				if ($actions) {
					$userplusnetwork_notification_actions = array_intersect($userplusnetwork_notification_actions, $actions);
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
						array_to_quoted_string($userplusnetwork_notification_actions),
						$user_plus_network_where,
						$wpdb->activity_log
					);
				}

				// Tags actions: User + Network Notifications:
				// Notify the user when:
				// - Someone from the network subscribes to a #hashtag
				$userplusnetwork_notification_tag_actions = apply_filters(
					'AAL_PoP_API:notifications:userplusnetwork-tag:actions',
					array(
						AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG,
						AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG,
					)
				);
				if ($actions) {
					$userplusnetwork_notification_tag_actions = array_intersect($userplusnetwork_notification_tag_actions, $actions);
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
						array_to_quoted_string($userplusnetwork_notification_tag_actions),
						$user_plus_network_where,
						$wpdb->activity_log
					);
				}

				// The User is the target of the action Notifications:
				// Notify the user when:
				// - By Hook: A community updates the membership of the user (URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP)
				$useristarget_notification_actions = apply_filters(
					'AAL_PoP_API:notifications:useristarget:actions',
					array()
				);
				if ($actions) {
					$useristarget_notification_actions = array_intersect($useristarget_notification_actions, $actions);
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
						array_to_quoted_string($useristarget_notification_actions),
						$useristarget_where,
						$wpdb->activity_log
					);
				}

				// User Activity + Network Notifications:
				// Notify the user when:
				// - Anyone created a post related to the user's post
				$useractivityposts_notification_actions = apply_filters(
					'AAL_PoP_API:notifications:useractivityposts:actions',
					array(
						AAL_POP_ACTION_POST_REFERENCEDPOST,
					)
				);
				if ($actions) {
					$useractivityposts_notification_actions = array_intersect($useractivityposts_notification_actions, $actions);
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
						array_to_quoted_string($useractivityposts_notification_actions),
						$useractivityposts_where,
						$wpdb->activity_log
					);
				}
				
				// User Activity + Network Notifications:
				// Notify the user when:
				// - Someone from the network posts
				// - Someone from the network recommends a post
				// - Anyone recommended the user's post
				$useractivityplusnetwork_notification_actions = apply_filters(
					'AAL_PoP_API:notifications:useractivityplusnetwork:actions',
					array(
						AAL_POP_ACTION_POST_CREATEDPOST,
						// AAL_POP_ACTION_POST_CREATEDPENDINGPOST,
						// AAL_POP_ACTION_POST_CREATEDDRAFTPOST,
						// AAL_POP_ACTION_POST_UPDATEDPOST,
						// AAL_POP_ACTION_POST_UPDATEDPENDINGPOST,
						// AAL_POP_ACTION_POST_UPDATEDDRAFTPOST,
						// AAL_POP_ACTION_POST_REMOVEDPOST,
						// Comment Leo 09/03/2016: action AAL_POP_ACTION_POST_COMMENTEDINPOST not needed, since we can achieve the same results only through action AAL_POP_ACTION_COMMENT_ADDED
						// AAL_POP_ACTION_POST_COMMENTEDINPOST,
						AAL_POP_ACTION_POST_RECOMMENDEDPOST,
						AAL_POP_ACTION_POST_UNRECOMMENDEDPOST,
						AAL_POP_ACTION_POST_UPVOTEDPOST,
						AAL_POP_ACTION_POST_UNDIDUPVOTEPOST,
						AAL_POP_ACTION_POST_DOWNVOTEDPOST,
						AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST,
					)
				);
				if ($actions) {
					$useractivityplusnetwork_notification_actions = array_intersect($useractivityplusnetwork_notification_actions, $actions);
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
						array_to_quoted_string($useractivityplusnetwork_notification_actions),
						$useractivityposts_where,
						$user_plus_network_where,
						$wpdb->activity_log,
						$wpdb->posts
					);
				}

				// Admin Notifications:
				// Notify the user when:
				// - The admin approved the user's post
				// - The admin sent back to draft the user's post
				// - The admin trashed the user's post
				$admin_notification_postactions = apply_filters(
					'AAL_PoP_API:notifications:admin:post:actions',
					array(
						AAL_POP_ACTION_POST_APPROVEDPOST,
						AAL_POP_ACTION_POST_DRAFTEDPOST,
						AAL_POP_ACTION_POST_TRASHEDPOST,
						AAL_POP_ACTION_POST_SPAMMEDPOST,
					)
				);
				if ($actions) {
					$admin_notification_postactions = array_intersect($admin_notification_postactions, $actions);
				}
				if ($admin_notification_postactions) {
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
						array_to_quoted_string($admin_notification_postactions),
						$userposts_where,
						$wpdb->activity_log
					);
				}

				// Admin Notifications:
				// Notify the user when:
				// - The admin spammed the user's comment
				$admin_notification_commentactions = apply_filters(
					'AAL_PoP_API:notifications:admin:comment:actions',
					array(
						AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT,
					)
				);
				if ($actions) {
					$admin_notification_commentactions = array_intersect($admin_notification_commentactions, $actions);
				}
				if ($admin_notification_commentactions) {
					$sql_where_user_ors[] = sprintf(
						'
							%3$s.object_type = "Comments"
						AND
							%3$s.action in (
								%1$s
							)
						AND
							%2$s
						',
						array_to_quoted_string($admin_notification_commentactions),
						$usercomments_where,
						$wpdb->activity_log
					);
				}
				
				// Comment Notifications:
				// Notify the user when:
				// - Someone from the network comments, 
				// - Anyone comments in a post the user has activity in
				$usercommentsplusnetwork_notification_actions = apply_filters(
					'AAL_PoP_API:notifications:usercommentsplusnetwork:actions',
					array(
						AAL_POP_ACTION_COMMENT_ADDED,
					)
				);
				if ($actions) {
					$usercommentsplusnetwork_notification_actions = array_intersect($usercommentsplusnetwork_notification_actions, $actions);
				}
				if ($usercommentsplusnetwork_notification_actions) {
					
					// Make sure the post is published and the comment belongs to a published post or is not spam
					$sql_where_user_ors[] = sprintf(
						'
							%5$s.object_type = "Comments"
						AND
							%5$s.action in (
								%1$s
							)
						AND
							(
								%2$s
							OR
								%3$s
							OR
								%4$s
							)
						AND
							%5$s.object_id in (
								SELECT
									%6$s.comment_ID
								FROM
									%6$s
								INNER JOIN
									%7$s
								ON
									%6$s.comment_post_ID = %7$s.ID
								WHERE
									(
										%6$s.comment_approved = "1"
									AND
										%7$s.post_status = "publish"
									)
							)
						',
						array_to_quoted_string($usercommentsplusnetwork_notification_actions),
						$useractivityposts_where, //$usercomments_where,
						$user_plus_network_where,
						$useractivitycomments_where, 
						$wpdb->activity_log,
						$wpdb->comments,
						$wpdb->posts
					);
				}

				// Comment Leo 09/03/2016: instead of logging 2 actions (added comment + replied to comment),
				// we only log create comment, and customize the message for the user if comment is a response to his own comment
				// Otherwise, it creates trouble since the same person may receives 2 notifications
				// // Notify the user when:
				// // - Anyone replied to the user's comment
				// $sql_where_user_ors[] = sprintf(
				// 	'
				// 		%3$s.object_type = "Comments"
				// 	AND
				// 		%3$s.action in (
				// 			%1$s
				// 		)
				// 	AND
				// 		%2$s
				// 	',
				// 	array_to_quoted_string(array(
				// 		AAL_POP_ACTION_COMMENT_REPLIED,
				// 	)),
				// 	$usercomments_where,
				// 	$wpdb->activity_log
				// );
			

				// Allow plug-ins to keep adding WHERE statements
				$sql_where_user_ors = apply_filters('AAL_PoP_API:sql:wheres', $sql_where_user_ors, $args);

				$sql_where_user_ands = array();
				$sql_where_user_ands[] = '('.implode(') OR (', $sql_where_user_ors).')';

				// User registration date: show only notifications created after the user has registered
				// Otherwise, it will show the full activity history of his/her followed users, community members, etc. That's not cool
				if ($user_registered = get_the_author_meta('user_registered', $user_id)) {
				
					// user_registered date does not take into account the GMT, so add it again
					$user_registered_time = strtotime($user_registered) + (get_option('gmt_offset') * 3600);

					$sql_where_user_ands[] = sprintf(
						'
							%1$s.hist_time >= %2$s
						',
						$wpdb->activity_log,
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
						$wpdb->activity_log,
						$hist_time_compare,
						$hist_time
					);
				}

				// Allow plug-ins to keep adding WHERE statements
				$sql_where_user_ands = apply_filters('AAL_PoP_API:sql:where_ands', $sql_where_user_ands, $args);

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
			$paginate = $limit > 0;			
			if ($paginate) {

				$offset = ($paged - 1) * $limit;
				$paginationclause = sprintf(
					'
						LIMIT
							%s
						OFFSET
							%s
					',
					$limit,
					$offset
				);
			}
		}

		// Join the Log table with the Status table, on colums histid and user_id
		if ($user_id && $joinstatus) {

			if ($fields == '*') {
				$fields = sprintf(
					'%s.*, %s.*',
					$wpdb->activity_log,
					$wpdb->activity_log_status
				);
			}

			// Filter by status value?
			if ($status) {
				if (strtolower($status) == 'null') {
					$statuswhereclause = sprintf(
						'
							%1$s.status is null
						',
						$wpdb->activity_log_status
					);
				}
				else {
					$statuswhereclause = sprintf(
						'
							%1$s.status = "%2$s"
						',
						$wpdb->activity_log_status,
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
			// 	$status_value = '1=1';
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
				$wpdb->activity_log,
				$wpdb->activity_log_status,
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
			$wpdb->activity_log,
			$joinstatusclause,
			$sql_where,
			$orderclause,
			$paginationclause
		);
		$results = $wpdb->get_results($sql, $output);

		return $results;
	}

	public function get_notification($histid) {

		global $wpdb;
		$query = array(
			'include'		=> array($histid),
			'joinstatus'	=> false,
		);

		$results = $this->get_notifications($query);

		if ($results) {
			return $results[0];
		}

		return null;
	}

	public function delete_post_notifications($user_id, $post_id, $actions) {

		$this->delete_object_notifications($user_id, "Post", $post_id, $actions);
	}

	public function delete_user_notifications($user_id, $userobject_id, $actions) {

		$this->delete_object_notifications($user_id, "User", $userobject_id, $actions);
	}

	public function delete_object_notifications($user_id, $object_type, $object_id, $actions) {

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
			$wpdb->activity_log,
			$user_id,
			$object_type,
			$object_id,
			array_to_quoted_string($actions)
		);
		if ($results = $wpdb->get_results($sql)) {

			$histids = array();
			foreach ($results as $result) {
				$histids[] = $result->histid;
			}

			$this->delete_notifications($histids);
		}
	}

	public function clear_user($user_id) {

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
			$wpdb->activity_log,
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
					$wpdb->activity_log,
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
				$wpdb->activity_log_status,
				'('.implode(') OR (', $status_sql_where_ors).')'
			)
		);
	}

	public function delete_notifications($histids) {

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
				$wpdb->activity_log,
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
				$wpdb->activity_log_status,
				$joined_histids
			)
		);
	}

	public function clear_comment($comment_id) {

		// Function called when a comment is deleted from the system
		global $wpdb;

		// Find the $histid to delete, since they'll have to be deleted in 2 tables: activity_log and activity_log_status
		$sql = $wpdb->prepare(
			'
			SELECT
				histid
			FROM
				`%1$s`
			WHERE 
				(
					`object_type` = "Comments"
				AND
					`object_id` = %2$d
				)
			',
			$wpdb->activity_log,
			$comment_id
		);
		if ($results = $wpdb->get_results($sql)) {

			$histids = array();
			foreach ($results as $result) {
				$histids[] = $result->histid;
			}

			$this->delete_notifications($histids);
		}
	}

	public function set_status($histid, $user_id, $status = null) {

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
				$wpdb->activity_log_status,
				$histid,
				$user_id
			)
		);

		// If status is null => mark as unread => already done
		// If not null, then insert it in the DB
		if ($status) {

			$wpdb->insert(
				$wpdb->activity_log_status,
				array(
					'status_histid'		=> $histid,
					'status_user_id'   	=> $user_id,
					'status' 			=> $status,
				),
				array('%d', '%d', '%s')
			);	

			// Set the status for all additional (similar) notifications
			$additional = $this->set_status_multiple_notifications($user_id, $status, $histid);
			$ret = array_merge(
				$ret,
				$additional
			);
		}

		return $ret;
	}

	public function set_status_multiple_notifications($user_id, $status = AAL_POP_STATUS_READ, $histid = null) {

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
			$notification = $this->get_notification($histid);
			$objectids_sql = '';
			if ($notification->object_type == "Comments") {

				// Comments: since the URL to them is their post, so many comments on the same post can all be marked as read
				// Allow to override all actions
				$comment_actions = apply_filters(
					'AAL_PoP_API:additional_notificatios:markasread:comments:actions',
					array(
						AAL_POP_ACTION_COMMENT_ADDED,
					)
				);
				if (in_array($notification->action, $comment_actions)) {

					// Only approved comments
					$comment = get_comment($notification->object_id);
					$actions = $comment_actions;
					$objectids_sql = sprintf(
						'
							%1$s.object_id in (
								SELECT
									comment_ID
								FROM
									`%2$s`
								WHERE
									(
										comment_post_ID = %3$s
									AND
										comment_approved = "1"
									)
							)
						',
						$wpdb->activity_log,
						$wpdb->comments,
						$comment->comment_post_ID
					);
				}
			}
			elseif ($notification->object_type == "Post") {

				$post_actions = apply_filters(
					'AAL_PoP_API:additional_notificatios:markasread:posts:actions',
					array(
						AAL_POP_ACTION_POST_CREATEDPOST, // This works only for different user_id co-creating the same post
						AAL_POP_ACTION_POST_UPDATEDPOST,
						AAL_POP_ACTION_POST_RECOMMENDEDPOST,
						AAL_POP_ACTION_POST_UNRECOMMENDEDPOST,
						AAL_POP_ACTION_POST_UPVOTEDPOST,
						AAL_POP_ACTION_POST_UNDIDUPVOTEPOST,
						AAL_POP_ACTION_POST_DOWNVOTEDPOST,
						AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST,
						AAL_POP_ACTION_POST_REFERENCEDPOST,
					)
				);
				if (in_array($notification->action, $post_actions)) {				

					$actions = $post_actions;
					$objectids_sql = sprintf(
						'
							%1$s.object_id = %2$s
						',
						$wpdb->activity_log,
						$notification->object_id
					);
				}
			}
			elseif ($notification->object_type == "User") {

				$user_actions = apply_filters(
					'AAL_PoP_API:additional_notificatios:markasread:users:actions',
					array(
						AAL_POP_ACTION_USER_FOLLOWSUSER,
						AAL_POP_ACTION_USER_UNFOLLOWSUSER,
					)
				);
				$sameuser_actions = apply_filters(
					'AAL_PoP_API:additional_notificatios:markasread:sameusers:actions',
					array()
				);
				if (in_array($notification->action, $user_actions)) {

					$actions = $user_actions;
					$objectids_sql = sprintf(
						'
							%1$s.object_id = %2$s
						',
						$wpdb->activity_log,
						$notification->object_id
					);
				}
				elseif (in_array($notification->action, $sameuser_actions)) {

					$actions = $sameuser_actions;
					$objectids_sql = sprintf(
						'
							%1$s.object_id = %2$s
						AND
							%1$s.user_id = %3$s
						',
						$wpdb->activity_log,
						$notification->object_id,
						$notification->user_id
					);
				}
			}

			if ($objectids_sql) {
				
				// Generate the SQL and pass it to the filter
				// Comment Leo 10/03/2016: there should be no need to ask for %1$s.action = $notification->user_id in this SQL,
				// since going to the single post the user will see all comments, from all users.
				// Problem is, the user will not get notifications from everyone, so it's wrong to get all notification from all user_id for the same object_id,
				// So we keep it simple and conservative, and we also check for the same user_id
				$this->additionalnotifications_sql = sprintf(
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
					$wpdb->activity_log,
					$histid,
					$notification->action,
					$notification->object_type,
					$objectids_sql
				);
			}
		}

		// Get the notifications, limiting the results for the additional notifications, 
		// or not limiting for "mark all as read"
		if ($this->additionalnotifications_sql) {

			// Add a filter to add an extra AND statement, remove it immediately after
			add_filter(
				'AAL_PoP_API:sql:where_ands',
				array($this, 'add_additionalnotifications_status_sql'),
				10,
				2
			);
		}
		$args = array(
			// Bring all the results
			'limit' => 0,
			// Only bring notifications which are not read already
			'status' => 'null',
			// Only the IDs are needed
			'fields' => array(
				sprintf('%s.histid', $wpdb->activity_log),
				// sprintf('%s.status', $wpdb->activity_log_status)
			),
			'user_id' => $user_id,
		);
		$results = $this->get_notifications($args, $actions);
		if ($this->additionalnotifications_sql) {
			remove_filter(
				'AAL_PoP_API:sql:where_ands',
				array($this, 'add_additionalnotifications_status_sql'),
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
					$wpdb->activity_log_status,
					array(
						'status_histid'		=> $result->histid,
						'status_user_id'   	=> $user_id,
						'status' 			=> $status,
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

	function add_additionalnotifications_status_sql($sql_where_user_ands, $args) {

		$sql_where_user_ands[] = $this->additionalnotifications_sql;
		return $sql_where_user_ands;
	}
}