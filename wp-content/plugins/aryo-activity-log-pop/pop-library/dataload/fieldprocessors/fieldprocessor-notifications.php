<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_FIELDPROCESSOR_NOTIFICATIONS', 'notifications');
 
class GD_DataLoad_FieldProcessor_Notifications extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_NOTIFICATIONS;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_NOTIFICATIONS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$notification = $resultitem;	
		$vars = GD_TemplateManager_Utils::get_vars();
					
		switch ($field) {
		
			case 'id' :
				$value = $this->get_id($notification);
				break;
			case 'action' :
				$value = $notification->action;
				break;
			case 'object-type' :
				$value = $notification->object_type;
				break;
			case 'object-subtype' :
				$value = $notification->object_subtype;
				break;
			case 'object-name' :
				$value = $notification->object_name;
				break;
			case 'object-id' :
				$value = $notification->object_id;
				break;
			case 'user-id' :
				$value = $notification->user_id;
				break;
			case 'user-id' :
				$value = $notification->user_id;
				break;
			case 'user-caps' :
				$value = $notification->user_caps;
				break;
			case 'hist-ip' :
				$value = $notification->hist_ip;
				break;
			case 'hist-time' :
				$value = $notification->hist_time;
				break;
			case 'hist-time-nogmt' :
				// In the DB, the time is saved without GMT. However, in the front-end we need the GMT factored in,
				// because moment.js will
				$value = $notification->hist_time - (get_option('gmt_offset') * 3600);
				break;

			// Specific fields to be used by the subcomponents, based on a combination of Object Type + Action
			// Needed to, for instance, load the comment immediately, already from the notification
			case 'comment-object-id' :

				// Only when doing loadLatest. Otherwise, do not bring the comment data
				if (GD_TemplateManager_Utils::loading_latest()) {

					switch ($notification->object_type) {

						case 'Comments':
							
							// It doesn't work for AAL_POP_ACTION_COMMENT_REPLIED, since the object_id is the parent comment
							switch ($notification->action) {
								
								case AAL_POP_ACTION_COMMENT_ADDED:
									
									// comment-object-id is the object-id
									$value = $this->get_value($resultitem, 'object-id');
									break;
							}
							break;
					}
				}

				break;

			case 'status' :
				$value = $notification->status;
				if (!$value) {
					// Make sure to return an empty string back, since this is used as a class
					$value = '';
				}
				break;

			case 'is-status-read' :
				$status = $this->get_value($resultitem, 'status');
				$value = ($status == AAL_POP_STATUS_READ);
				break;

			case 'is-status-not-read' :
				$is_read = $this->get_value($resultitem, 'is-status-read');
				$value = !$is_read;
				break;

			case 'mark-as-read-url' :
				$value = add_query_arg('nid', $this->get_id($notification), get_permalink(POP_AAL_PAGE_NOTIFICATIONS_MARKASREAD));
				break;

			case 'mark-as-unread-url' :
				$value = add_query_arg('nid', $this->get_id($notification), get_permalink(POP_AAL_PAGE_NOTIFICATIONS_MARKASUNREAD));
				break;

			case 'icon' :
				
				// URL depends basically on the action performed on the object type
				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_APPROVEDPOST:
							case AAL_POP_ACTION_POST_DRAFTEDPOST:
							case AAL_POP_ACTION_POST_TRASHEDPOST:
							case AAL_POP_ACTION_POST_SPAMMEDPOST:
							case AAL_POP_ACTION_POST_UNDIDUPVOTEPOST:
							case AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST:

								$icons = array(
									AAL_POP_ACTION_POST_APPROVEDPOST => 'fa-check',
									AAL_POP_ACTION_POST_DRAFTEDPOST => 'fa-exclamation',
									AAL_POP_ACTION_POST_TRASHEDPOST => 'fa-trash',
									AAL_POP_ACTION_POST_SPAMMEDPOST => 'fa-trash',
									AAL_POP_ACTION_POST_UNDIDUPVOTEPOST => 'fa-remove',
									AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST => 'fa-remove',
								);
								$value = $icons[$notification->action];
								break;

							case AAL_POP_ACTION_POST_REFERENCEDPOST:
							// case AAL_POP_ACTION_POST_COMMENTEDINPOST:
							case AAL_POP_ACTION_POST_RECOMMENDEDPOST:
							case AAL_POP_ACTION_POST_UNRECOMMENDEDPOST:
							case AAL_POP_ACTION_POST_UPVOTEDPOST:
							case AAL_POP_ACTION_POST_DOWNVOTEDPOST:
								
								$pages = array(
									AAL_POP_ACTION_POST_REFERENCEDPOST => POP_COREPROCESSORS_PAGE_RELATEDCONTENT,
									// AAL_POP_ACTION_POST_COMMENTEDINPOST => POP_WPAPI_PAGE_ADDCOMMENT,
									AAL_POP_ACTION_POST_RECOMMENDEDPOST => POP_COREPROCESSORS_PAGE_RECOMMENDPOST,
									AAL_POP_ACTION_POST_UNRECOMMENDEDPOST => POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST,
									AAL_POP_ACTION_POST_UPVOTEDPOST => POP_COREPROCESSORS_PAGE_UPVOTEPOST,
									AAL_POP_ACTION_POST_DOWNVOTEDPOST => POP_COREPROCESSORS_PAGE_DOWNVOTEPOST,
								);
								$value = gd_navigation_menu_item($pages[$notification->action], false);
								break;
								
							default:
								$value = gd_get_posticon($notification->object_id);
								break;
						}
						break;

					case 'User':

						switch ($notification->action) {

							case AAL_POP_ACTION_USER_WELCOMENEWUSER:
								$value = gd_navigation_menu_item(POP_AAL_PAGEALIAS_USERWELCOME, false);
								break;

							case AAL_POP_ACTION_USER_FOLLOWSUSER:
								$value = gd_navigation_menu_item(POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS, false);
								break;

							case AAL_POP_ACTION_USER_UNFOLLOWSUSER:
								$value = 'fa-remove';
								break;

							case AAL_POP_ACTION_USER_CHANGEDPASSWORD:
							case AAL_POP_ACTION_USER_UPDATEDPROFILE:
							case AAL_POP_ACTION_USER_UPDATEDPHOTO:
							case AAL_POP_ACTION_USER_LOGGEDIN:
							case AAL_POP_ACTION_USER_LOGGEDOUT:

								$pages = array(
									AAL_POP_ACTION_USER_CHANGEDPASSWORD => POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE,
									AAL_POP_ACTION_USER_UPDATEDPROFILE => POP_WPAPI_PAGE_EDITPROFILE,
									AAL_POP_ACTION_USER_UPDATEDPHOTO => POP_WPAPI_PAGE_EDITAVATAR,
									AAL_POP_ACTION_USER_LOGGEDIN => POP_WPAPI_PAGE_LOGIN,
									AAL_POP_ACTION_USER_LOGGEDOUT => POP_WPAPI_PAGE_LOGOUT,
								);
								$value = gd_navigation_menu_item($pages[$notification->action], false);
								break;
						}
						break;

					case 'Taxonomy':

						switch ($notification->object_subtype) {

							case 'Tag':

								switch ($notification->action) {

									case AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG:
										$value = gd_navigation_menu_item(POP_COREPROCESSORS_PAGE_SUBSCRIBERS, false);
										break;

									case AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG:
										$value = 'fa-remove';
										break;
								}
								break;
						}
						break;

					case 'Comments':

						switch ($notification->action) {

							case AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT:

								$icons = array(
									AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT => 'fa-trash',
								);
								$value = $icons[$notification->action];
								break;

							default:
								$value = gd_navigation_menu_item(POP_WPAPI_PAGE_ADDCOMMENT, false);
								break;
						}
						break;
				}
				break;

			case 'url' :

				// URL depends basically on the action performed on the object type
				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_REFERENCEDPOST:

								// Can't point to the posted article since we don't have the information (object_id is the original, referenced post, not the referencing one),
								// so the best next thing is to point to the tab of all related content of the original post
								$value = GD_TemplateManager_Utils::add_tab(get_permalink($notification->object_id), POP_COREPROCESSORS_PAGE_RELATEDCONTENT);
								break;

							case AAL_POP_ACTION_POST_DRAFTEDPOST:
							case AAL_POP_ACTION_POST_CREATEDPENDINGPOST:
							case AAL_POP_ACTION_POST_CREATEDDRAFTPOST:

								$value = get_edit_post_link($notification->object_id);
								break;

							case AAL_POP_ACTION_POST_TRASHEDPOST:
							case AAL_POP_ACTION_POST_SPAMMEDPOST:

								$pages = array(
									AAL_POP_ACTION_POST_TRASHEDPOST => POP_WPAPI_PAGE_MYCONTENT,
									AAL_POP_ACTION_POST_SPAMMEDPOST => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES,
								);
								$value = get_permalink($pages[$notification->action]);
								break;

							default:
								$value = get_permalink($notification->object_id);
								break;
						}
						break;

					case 'User':

						switch ($notification->action) {

							case AAL_POP_ACTION_USER_FOLLOWSUSER:
							case AAL_POP_ACTION_USER_UNFOLLOWSUSER:

								// If the user is the object of this action, then point the link to the user who is doing the action
								if ($vars['global-state']['current-user-id']/*get_current_user_id()*/ == $notification->object_id) {
									$value = get_author_posts_url($notification->user_id);
								}
								else {
									$value = get_author_posts_url($notification->object_id);
								}
								break;

							case AAL_POP_ACTION_USER_WELCOMENEWUSER:
								$value = get_permalink(POP_AAL_PAGEALIAS_USERWELCOME);
								break;

							case AAL_POP_ACTION_USER_CHANGEDPASSWORD:
								$value = null;
								break;

							case AAL_POP_ACTION_USER_UPDATEDPROFILE:
							case AAL_POP_ACTION_USER_UPDATEDPHOTO:
							case AAL_POP_ACTION_USER_LOGGEDIN:
							case AAL_POP_ACTION_USER_LOGGEDOUT:
								$value = get_author_posts_url($notification->user_id);
								break;

							default:
								$value = get_author_posts_url($notification->object_id);
								break;
						}
						break;

					case 'Taxonomy':

						switch ($notification->object_subtype) {

							case 'Tag':

								switch ($notification->action) {

									case AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG:
									case AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG:

										$value = get_tag_link($notification->object_id);
										break;

									default:
										$value = get_tag_link($notification->object_id);
										break;
								}
								break;

							default:
								$value = get_term_link($notification->object_id);
								break;
						}
						break;

					case 'Comments':

						switch ($notification->action) {

							case AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT:

								$pages = array(
									AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES,
								);
								$value = get_permalink($pages[$notification->action]);
								break;

							default:
								$comment = get_comment($notification->object_id);
								$value = get_permalink($comment->comment_post_ID);
								break;
						}
						break;
				}
				break;

			case 'message' :

				// Message depends basically on the action performed on the object type
				switch ($notification->object_type) {

					case 'Post':

						switch ($notification->action) {

							case AAL_POP_ACTION_POST_CREATEDPOST:
							case AAL_POP_ACTION_POST_CREATEDPENDINGPOST:
							case AAL_POP_ACTION_POST_CREATEDDRAFTPOST:

								// Show "posted" or "co-authored", depending on if the post has more than 1 author, and the main author is that on the first position of the array
								$authors = gd_get_postauthors($notification->object_id);
								if (count($authors) > 1 && $authors[0] != $notification->user_id) {

									$messages = array(
										AAL_POP_ACTION_POST_CREATEDPOST => __('<strong>%1$s</strong> co-authored %2$s <strong>%3$s</strong>', 'aal-pop'),
										AAL_POP_ACTION_POST_CREATEDPENDINGPOST => __('<strong>%1$s</strong> co-authored “pending” %2$s <strong>%3$s</strong>', 'aal-pop'),
										AAL_POP_ACTION_POST_CREATEDDRAFTPOST => __('<strong>%1$s</strong> co-authored “draft” %2$s <strong>%3$s</strong>', 'aal-pop'),
									);
								}
								else {

									// If the user has been tagged in this post, this action has higher priority than creating a post, then show that message
									$taggedusers_ids = GD_MetaManager::get_post_meta($notification->object_id, GD_METAKEY_POST_TAGGEDUSERS);
									if (in_array($vars['global-state']['current-user-id']/*get_current_user_id()*/, $taggedusers_ids)) {

										$messages = array(
											AAL_POP_ACTION_POST_CREATEDPOST => __('<strong>%1$s</strong> mentioned you in %2$s <strong>%3$s</strong>', 'aal-pop'),
											AAL_POP_ACTION_POST_CREATEDPENDINGPOST => __('<strong>%1$s</strong> mentioned you in “pending” %2$s <strong>%3$s</strong>', 'aal-pop'),
											AAL_POP_ACTION_POST_CREATEDDRAFTPOST => __('<strong>%1$s</strong> mentioned you in “draft” %2$s <strong>%3$s</strong>', 'aal-pop'),
										);
									}
									else {

										$messages = array(
											AAL_POP_ACTION_POST_CREATEDPOST => __('<strong>%1$s</strong> created %2$s <strong>%3$s</strong>', 'aal-pop'),
											AAL_POP_ACTION_POST_CREATEDPENDINGPOST => __('<strong>%1$s</strong> created “pending” %2$s <strong>%3$s</strong>', 'aal-pop'),
											AAL_POP_ACTION_POST_CREATEDDRAFTPOST => __('<strong>%1$s</strong> created “draft” %2$s <strong>%3$s</strong>', 'aal-pop'),
										);

										// If the post has #hashtags the user is subscribed to, then add it as part of the message (the notification may appear only because of the #hashtag)
										$post_tags = wp_get_post_tags($notification->object_id, array('fields' => 'ids'));
										$user_hashtags = GD_MetaManager::get_user_meta($vars['global-state']['current-user-id']/*get_current_user_id()*/, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);
										if ($intersected_tags = array_intersect($post_tags, $user_hashtags)) {

											$tags = array();
											foreach ($intersected_tags as $tag_id) {
												
												$tag = get_tag($tag_id);
												$tags[] = PoP_TagUtils::get_tag_symbol().$tag->name;
											}
											foreach ($messages as $action => $message) {
												$messages[$action] = sprintf(
													__('%1$s (<em>tags: <strong>%2$s</strong></em>)', 'aal-pop'),
													$message,
													implode(__(', ', 'aal-pop'), $tags)
												);
											}
										}
									}
								}
								$value = sprintf(
									$messages[$notification->action],
									get_the_author_meta('display_name', $notification->user_id),
									gd_get_postname($notification->object_id, 'lc'),
									$notification->object_name
								);
								break;

							case AAL_POP_ACTION_POST_UPDATEDPOST:
							case AAL_POP_ACTION_POST_UPDATEDPENDINGPOST:
							case AAL_POP_ACTION_POST_UPDATEDDRAFTPOST:
							case AAL_POP_ACTION_POST_REMOVEDPOST:
							// case AAL_POP_ACTION_POST_COMMENTEDINPOST:
							case AAL_POP_ACTION_POST_REFERENCEDPOST:
							case AAL_POP_ACTION_POST_RECOMMENDEDPOST:
							case AAL_POP_ACTION_POST_UNRECOMMENDEDPOST:
							case AAL_POP_ACTION_POST_UPVOTEDPOST:
							case AAL_POP_ACTION_POST_UNDIDUPVOTEPOST:
							case AAL_POP_ACTION_POST_DOWNVOTEDPOST:
							case AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST:

								$messages = array(
									AAL_POP_ACTION_POST_UPDATEDPOST => __('<strong>%1$s</strong> updated %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_UPDATEDPENDINGPOST => __('<strong>%1$s</strong> updated “pending” %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_UPDATEDDRAFTPOST => __('<strong>%1$s</strong> updated “draft” %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_REMOVEDPOST => __('<strong>%1$s</strong> removed %2$s <strong>%3$s</strong>', 'aal-pop'),
									// AAL_POP_ACTION_POST_COMMENTEDINPOST => __('<strong>%1$s</strong> commented in %2$s <strong>%s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_REFERENCEDPOST => __('<strong>%1$s</strong> posted content related to %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_RECOMMENDEDPOST => __('<strong>%1$s</strong> has recommended %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_UNRECOMMENDEDPOST => __('<strong>%1$s</strong> stopped recommending %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_UPVOTEDPOST => __('<strong>%1$s</strong> upvoted %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_UNDIDUPVOTEPOST => __('<strong>%1$s</strong> stopped upvoting %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_DOWNVOTEDPOST => __('<strong>%1$s</strong> downvoted %2$s <strong>%3$s</strong>', 'aal-pop'),
									AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST => __('<strong>%1$s</strong> stopped downvoting %2$s <strong>%3$s</strong>', 'aal-pop'),
								);
								$value = sprintf(
									$messages[$notification->action],
									get_the_author_meta('display_name', $notification->user_id),
									gd_get_postname($notification->object_id, 'lc'),//strtolower(gd_get_postname($notification->object_id)),
									$notification->object_name
								);
								break;

							case AAL_POP_ACTION_POST_APPROVEDPOST:
							case AAL_POP_ACTION_POST_DRAFTEDPOST:
							case AAL_POP_ACTION_POST_TRASHEDPOST:
							case AAL_POP_ACTION_POST_SPAMMEDPOST:

								$messages = array(
									AAL_POP_ACTION_POST_APPROVEDPOST => __('Your %1$s <strong>%2$s</strong> was approved', 'aal-pop'),
									AAL_POP_ACTION_POST_DRAFTEDPOST => __('Your %1$s <strong>%2$s</strong> was sent back to “draft”, please review it', 'aal-pop'),
									AAL_POP_ACTION_POST_TRASHEDPOST => __('Your %1$s <strong>%2$s</strong> was deleted', 'aal-pop'),
									AAL_POP_ACTION_POST_SPAMMEDPOST => __('Your %1$s <strong>%2$s</strong> was deleted for not adhering to our content guidelines', 'aal-pop'),
								);
								$value = sprintf(
									$messages[$notification->action],
									gd_get_postname($notification->object_id, 'lc'),//strtolower(gd_get_postname($notification->object_id)),
									$notification->object_name
								);
								break;

							default:

								$value = $notification->object_name;
								break;
						}
						break;

					case 'User':

						switch ($notification->action) {

							case AAL_POP_ACTION_USER_FOLLOWSUSER:
							case AAL_POP_ACTION_USER_UNFOLLOWSUSER:

								$messages = array(
									AAL_POP_ACTION_USER_FOLLOWSUSER => __('<strong>%s</strong> is now following %s', 'aal-pop'),
									AAL_POP_ACTION_USER_UNFOLLOWSUSER => __('<strong>%s</strong> stopped following %s', 'aal-pop'),
								);
								
								// Change the message depending if the logged in user is the object of this action
								$recipient = ($vars['global-state']['current-user-id']/*get_current_user_id()*/ == $notification->object_id) ? __('you', 'aal-pop') : sprintf('<strong>%s</strong>', get_the_author_meta('display_name', $notification->object_id));
								$value = sprintf(
									$messages[$notification->action],
									get_the_author_meta('display_name', $notification->user_id),
									$recipient
								);
								break;

							case AAL_POP_ACTION_USER_WELCOMENEWUSER:
							
								$value = sprintf(
									__('<strong>Welcome to %s, %s!</strong><br/>Check out here what is the purpose of this website', 'aal-pop'),
									get_bloginfo('name'),
									$notification->object_name //get_the_author_meta('display_name', $notification->object_id),
								);
								break;

							case AAL_POP_ACTION_USER_CHANGEDPASSWORD:
							case AAL_POP_ACTION_USER_UPDATEDPROFILE:
							case AAL_POP_ACTION_USER_UPDATEDPHOTO:
							case AAL_POP_ACTION_USER_LOGGEDIN:
							case AAL_POP_ACTION_USER_LOGGEDOUT:

								$messages = array(
									AAL_POP_ACTION_USER_CHANGEDPASSWORD => __('<strong>%s</strong> changed the password', 'aal-pop'),
									AAL_POP_ACTION_USER_UPDATEDPROFILE => __('<strong>%s</strong> updated the user profile', 'aal-pop'),
									AAL_POP_ACTION_USER_UPDATEDPHOTO => __('<strong>%s</strong> updated the user photo', 'aal-pop'),
									AAL_POP_ACTION_USER_LOGGEDIN => __('<strong>%s</strong> logged in', 'aal-pop'),
									AAL_POP_ACTION_USER_LOGGEDOUT => __('<strong>%s</strong> logged out', 'aal-pop'),
								);
								$value = sprintf(
									$messages[$notification->action],
									get_the_author_meta('display_name', $notification->user_id)
								);
								break;

							default:

								$value = $notification->object_name;
								break;
						}
						break;

					case 'Taxonomy':

						switch ($notification->object_subtype) {

							case 'Tag':

								switch ($notification->action) {

									case AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG:
									case AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG:

										$messages = array(
											AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG => __('<strong>%s</strong> subscribed to <strong>%s</strong>', 'aal-pop'),
											AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG => __('<strong>%s</strong> unsubscribed from <strong>%s</strong>', 'aal-pop'),
										);
										
										$tag = get_tag($notification->object_id);
										$value = sprintf(
											$messages[$notification->action],
											get_the_author_meta('display_name', $notification->user_id),
											PoP_TagUtils::get_tag_symbol().$tag->name
										);
										break;

									default:

										$value = $notification->object_name;
										break;
								}
								break;

							default:

								$value = $notification->object_name;
								break;
						}
						break;

					case 'Comments':

						switch ($notification->action) {

							case AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT:

								$comment = get_comment($notification->object_id);
								$messages = array(
									AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT => __('Your comment on %1$s <strong>%2$s</strong> was deleted for not adhering to our content guidelines', 'aal-pop'),
								);
								$value = sprintf(
									$messages[$notification->action],
									gd_get_postname($comment->comment_post_ID, 'lc'),
									get_the_title($comment->comment_post_ID)
								);
								break;

							case AAL_POP_ACTION_COMMENT_ADDED:

								$comment = get_comment($notification->object_id);

								// Change the message if the comment is a response to the user's comment
								$message = __('<strong>%1$s</strong> commented in %2$s <strong>%3$s</strong>', 'aal-pop');
								if ($comment->comment_parent) {

									$comment_parent = get_comment($comment->comment_parent);
									if ($comment_parent->user_id == $vars['global-state']['current-user-id']/*get_current_user_id()*/) {
										$message = __('<strong>%1$s</strong> replied to your comment in %2$s <strong>%3$s</strong>', 'aal-pop');
									}
								}

								// If the user has been tagged in this comment, this action has higher priority than commenting, then show that message
								$taggedusers_ids = GD_MetaManager::get_comment_meta($notification->object_id, GD_METAKEY_COMMENT_TAGGEDUSERS);
								if (in_array($vars['global-state']['current-user-id']/*get_current_user_id()*/, $taggedusers_ids)) {

									$message = __('<strong>%1$s</strong> mentioned you in a comment in %2$s <strong>%3$s</strong>', 'aal-pop');
								}
								$value = sprintf(
									$message,
									get_the_author_meta('display_name', $notification->user_id),
									gd_get_postname($comment->comment_post_ID, 'lc'),
									get_the_title($comment->comment_post_ID)
								);
								break;

							// case AAL_POP_ACTION_COMMENT_REPLIED:
							// 	$comment = get_comment($notification->object_id);
							// 	$value = sprintf(
							// 		'<strong>%s</strong> replied to your comment in %s <strong>%s</strong>',
							// 		get_the_author_meta('display_name', $notification->user_id),//$comment->comment_author,
							// 		gd_get_postname($comment->comment_post_ID, 'lc'), //strtolower(gd_get_postname($notification->object_id)),
							// 		get_the_title($comment->comment_post_ID)//$notification->object_name
							// 	);
							// 	break;
						}
						break;

					default:

						$value = $notification->object_name;
						break;
				}
				break;
		
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	

	function get_id($resultitem) {
	
		return $resultitem->histid;		
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Notifications();