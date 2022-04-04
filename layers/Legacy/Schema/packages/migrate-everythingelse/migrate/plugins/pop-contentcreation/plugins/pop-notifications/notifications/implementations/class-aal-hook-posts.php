<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_ContentCreation_Notifications_Hook_Posts /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // Created/Updated/Removed Post
        \PoP\Root\App::addAction(AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE, $this->createdPost(...));
        \PoP\Root\App::addAction(AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_UPDATE, $this->updatedPost(...), 10, 2);
        \PoP\Root\App::addAction(
            'popcms:transitionPostStatus',
            $this->removedPost(...),
            10,
            3
        );

        // Admin approval
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if ($cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction(
                'popcms:transitionPostStatus',
                $this->adminApprovalPost(...),
                10,
                3
            );
        }

        // parent::__construct();
    }

    protected function skipNotificationForPost($post_id)
    {

        // Check if the post needs or not be notified (eg: Highlights must not, since they have their own action)
        // If the post type is not allowed, then skip. Otherwise, by default, create the notification
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        $skip = !in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes());
        return \PoP\Root\App::applyFilters(
            'PoP_ContentCreation_Notifications_Hook_Posts:skipNotificationForPost',
            $skip,
            $post_id
        );
    }

    public function createdPost($post_id)
    {
        if ($this->skipNotificationForPost($post_id)) {
            return;
        }

        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post_status = $customPostTypeAPI->getStatus($post_id);
        if ($post_status == Status::PUBLISHED) {
            $this->logCreatedPost($post_id);
        } elseif ($post_status == Status::PENDING) {
            $this->logByPostAuthors($post_id, AAL_POP_ACTION_POST_CREATEDPENDINGPOST);
        } elseif ($post_status == Status::DRAFT) {
            $this->logByPostAuthors($post_id, AAL_POP_ACTION_POST_CREATEDDRAFTPOST);
        }
    }

    public function updatedPost($post_id, $log)
    {
        if ($this->skipNotificationForPost($post_id)) {
            return;
        }

        // Is it being created? (Eg: first created as draft, then "updated" to status publish)
        // Then trigger event Create, not Update
        // Simply check the previous status, if it was not published then trigger Create
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post_status = $customPostTypeAPI->getStatus($post_id);
        if ($post_status == Status::PUBLISHED) {
            if ($log['previous-status'] != Status::PUBLISHED) {
                $this->logCreatedPost($post_id);
            } else {
                $this->logByPostAuthors($post_id, AAL_POP_ACTION_POST_UPDATEDPOST);
            }
        } elseif ($post_status == Status::PENDING) {
            $this->logByPostAuthors($post_id, AAL_POP_ACTION_POST_UPDATEDPENDINGPOST);
        } elseif ($post_status == Status::DRAFT) {
            $this->logByPostAuthors($post_id, AAL_POP_ACTION_POST_UPDATEDDRAFTPOST);
        }
    }

    protected function logCreatedPost($post_id)
    {

        // Delete all previous notifications of "User created this post"
        // It might happen that the user has Published a post, then took it to draft, then published it again
        // So, we need to delete the first notification, or it will be repeated
        $clear_actions = array(
            AAL_POP_ACTION_POST_CREATEDPOST,
        );

        // Allow for co-authors
        $authors = gdGetPostauthors($post_id);
        foreach ($authors as $author) {
            // AAL_Main::instance()->api->deletePostNotifications($author, $post_id, $clear_actions);
            PoP_Notifications_API::deletePostNotifications($author, $post_id, $clear_actions);
            // }
        }

        // Only after log the action
        $this->logByPostAuthors($post_id, AAL_POP_ACTION_POST_CREATEDPOST);
    }

    public function removedPost($new_status, $old_status, $post)
    {
        if ($old_status == Status::PUBLISHED && $new_status != Status::PUBLISHED) {
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $this->logByPostAuthors($customPostTypeAPI->getID($post), AAL_POP_ACTION_POST_REMOVEDPOST);
        }
    }

    protected function logByPostAuthors($post_id, $action)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post_title = $customPostTypeAPI->getTitle($post_id);

        // Allow for co-authors
        $authors = gdGetPostauthors($post_id);
        foreach ($authors as $author) {
            PoP_Notifications_Utils::insertLog(
                array(
                    'user_id' => $author,
                    'action' => $action,
                    'object_type' => 'Post',
                    'object_subtype' => $customPostTypeAPI->getCustomPostType($post_id),
                    'object_id' => $post_id,
                    'object_name' => $post_title,
                )
            );
        }
    }

    public function adminApprovalPost($new_status, $old_status, $post)
    {

        // Enable if the current logged in user is the System Notification's defined user
        if (\PoP\Root\App::getState('current-user-id') != POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS) {
            return;
        }

        $action = null;
        if ($old_status == Status::PENDING && $new_status == Status::PUBLISHED) {
            $action = AAL_POP_ACTION_POST_APPROVEDPOST;
        } elseif (in_array($old_status, array(Status::PENDING, Status::PUBLISHED)) && $new_status == Status::DRAFT) {
            $action = AAL_POP_ACTION_POST_DRAFTEDPOST;
        } elseif ($new_status == Status::TRASH) {
            // Posts trashed, there are 2 different possibilities:
            // 1. the post was just trashed, because, for instance, the user posted it twice
            // 2. the post was trashed because it was spam
            // However, we don't have post status "spam", so to differentiate between these 2 states, we must change the way we trash posts
            // For #1:
            // 1st: mark the post as "draft"
            // 2nd: from there, delete it
            // For #2: just delete it straight from "publish"
            if (in_array($old_status, array(Status::DRAFT, Status::PENDING))) {
                $action = AAL_POP_ACTION_POST_TRASHEDPOST;
            } elseif (in_array($old_status, array(Status::PUBLISHED))) {
                $action = AAL_POP_ACTION_POST_SPAMMEDPOST;
            }
        }

        if ($action) {
            // If any of these actions must be logged, then delete all previous admin_approval actions
            // This is needed for 2 reasons:
            // 1. Avoid duplicates (eg: pending to approval to pending to approval, just show the latest one will be enough)
            // 2. Stale state: a notification link saying "your post is approved" will point to the url, which might be invalid if later on the post was spammed
            $clear_actions = array(
                AAL_POP_ACTION_POST_APPROVEDPOST,
                AAL_POP_ACTION_POST_DRAFTEDPOST,
                AAL_POP_ACTION_POST_TRASHEDPOST,
                AAL_POP_ACTION_POST_SPAMMEDPOST,
            );
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $customPostID = $customPostTypeAPI->getID;
            // AAL_Main::instance()->api->deletePostNotifications(POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS, $customPostID, $clear_actions);
            $cmspostsresolver = \PoPCMSSchema\Posts\ObjectPropertyResolverFactory::getInstance();
            PoP_Notifications_API::deletePostNotifications(POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS, $customPostID, $clear_actions);

            // Only after log the action
            PoP_Notifications_Utils::logPostAction($customPostID, $action, POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS);
        }
    }
}
