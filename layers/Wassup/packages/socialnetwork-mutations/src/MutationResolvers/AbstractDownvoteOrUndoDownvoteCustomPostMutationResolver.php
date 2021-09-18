<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

abstract class AbstractDownvoteOrUndoDownvoteCustomPostMutationResolver extends AbstractCustomPostUpdateUserMetaValueMutationResolver
{
    protected function eligible($post)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $eligible = in_array($customPostTypeAPI->getCustomPostType($post), \PoP_SocialNetwork_Utils::getUpdownvotePostTypes());
        return $this->hooksAPI->applyFilters('GD_UpdownvoteUndoUpdownvotePost:eligible', $eligible, $post);
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, $form_data)
    {
        parent::additionals($target_id, $form_data);
        $this->hooksAPI->doAction('gd_downvoteundodownvote_post', $target_id, $form_data);
    }
}
