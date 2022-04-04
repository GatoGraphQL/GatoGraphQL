<?php
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;
use PoPCMSSchema\Users\ConditionalOnComponent\CustomPosts\Facades\CustomPostUserTypeAPIFacade;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_RelatedPosts_Notifications_Hook_Posts /* extends AAL_Hook_Base*/
{
    public function __construct()
    {
        // Referenced Post
        \PoP\Root\App::addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE,
            $this->createdPostRelatedToPost(...)
        );
        \PoP\Root\App::addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_UPDATE,
            $this->updatedPostRelatedToPost(...),
            10,
            2
        );
    }

    public function createdPostRelatedToPost($post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            if ($customPostTypeAPI->getStatus($post_id) == Status::PUBLISHED) {
                // Referenced posts: all of them for the new post
                $references = \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($post_id, GD_METAKEY_POST_REFERENCES);
                $this->relatedToPost($post_id, $references);
            }
        }
    }

    public function updatedPostRelatedToPost($post_id, $log)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            if ($customPostTypeAPI->getStatus($post_id) == Status::PUBLISHED) {
                // Referenced posts: if doing an update, pass only the newly added ones
                // If doing a create (changed "draft" to "publish"), then add all references
                if ($log['previous-status'] != Status::PUBLISHED) {
                    // This is a Create post
                    $references = \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($post_id, GD_METAKEY_POST_REFERENCES);
                } else {
                    // This is an Update post
                    $references = $log['new-references'];
                }
                $this->relatedToPost($post_id, $references);
            }
        }
    }

    protected function relatedToPost($post_id, $references)
    {

        // Referenced posts
        if ($references) {
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $customPostUserTypeAPI = CustomPostUserTypeAPIFacade::getInstance();
            foreach ($references as $reference_id) {
                PoP_Notifications_Utils::insertLog(
                    array(
                        'user_id' => $customPostUserTypeAPI->getAuthorID($post_id),
                        'action' => AAL_POP_ACTION_POST_REFERENCEDPOST,
                        'object_type' => 'Post',
                        'object_subtype' => $customPostTypeAPI->getCustomPostType($reference_id),
                        'object_id' => $reference_id,
                        'object_name' => $customPostTypeAPI->getTitle($reference_id),
                    )
                );
            }
        }
    }
}
