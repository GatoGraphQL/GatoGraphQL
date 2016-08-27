<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('AAL_POP_ACTION_POST_NOTIFIEDALLUSERS', 'notified_all_users');
define ('AAL_POP_ACTION_POST_CREATEDPOST', 'created_post');
define ('AAL_POP_ACTION_POST_CREATEDPENDINGPOST', 'created_pending_post');
define ('AAL_POP_ACTION_POST_CREATEDDRAFTPOST', 'created_draft_post');
define ('AAL_POP_ACTION_POST_UPDATEDPOST', 'updated_post');
define ('AAL_POP_ACTION_POST_UPDATEDPENDINGPOST', 'updated_pending_post');
define ('AAL_POP_ACTION_POST_UPDATEDDRAFTPOST', 'updated_draft_post');
define ('AAL_POP_ACTION_POST_REMOVEDPOST', 'removed_post');

define ('AAL_POP_ACTION_POST_APPROVEDPOST', 'approved_post');
define ('AAL_POP_ACTION_POST_DRAFTEDPOST', 'drafted_post');
define ('AAL_POP_ACTION_POST_TRASHEDPOST', 'trashed_post');
define ('AAL_POP_ACTION_POST_SPAMMEDPOST', 'spammed_post');

define ('AAL_POP_ACTION_POST_REFERENCEDPOST', 'referenced_post');
// Comment Leo 09/03/2016: action AAL_POP_ACTION_POST_COMMENTEDINPOST not needed, since we can achieve the same results only through action AAL_POP_ACTION_COMMENT_ADDED
// define ('AAL_POP_ACTION_POST_COMMENTEDINPOST', 'commented_in_post');
define ('AAL_POP_ACTION_POST_RECOMMENDEDPOST', 'recommended_post');
define ('AAL_POP_ACTION_POST_UNRECOMMENDEDPOST', 'unrecommended_post');
define ('AAL_POP_ACTION_POST_UPVOTEDPOST', 'upvoted_post');
define ('AAL_POP_ACTION_POST_UNDIDUPVOTEPOST', 'undid_upvote_post');
define ('AAL_POP_ACTION_POST_DOWNVOTEDPOST', 'downvoted_post');
define ('AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST', 'undid_downvote_post');

define ('AAL_POP_ACTION_USER_WELCOMENEWUSER', 'welcome_new_user');
define ('AAL_POP_ACTION_USER_FOLLOWSUSER', 'follows_user');
define ('AAL_POP_ACTION_USER_UNFOLLOWSUSER', 'unfollows_user');
define ('AAL_POP_ACTION_USER_CHANGEDPASSWORD', 'changed_password');
define ('AAL_POP_ACTION_USER_UPDATEDPROFILE', 'updated_profile');
define ('AAL_POP_ACTION_USER_UPDATEDPHOTO', 'updated_photo');

define ('AAL_POP_ACTION_USER_LOGGEDIN', 'logged_in');
define ('AAL_POP_ACTION_USER_LOGGEDOUT', 'logged_out');

define ('AAL_POP_ACTION_COMMENT_ADDED', 'added_comment');
define ('AAL_POP_ACTION_COMMENT_SPAMMEDCOMMENT', 'spammed_comment');

// Comment Leo 09/03/2016: instead of logging 2 actions (added comment + replied to comment),
// we only log create comment, and customize the message for the user if comment is a response to his own comment
// Otherwise, it creates trouble since the same person may receives 2 notifications
// define ('AAL_POP_ACTION_COMMENT_REPLIED', 'replied_comment');
