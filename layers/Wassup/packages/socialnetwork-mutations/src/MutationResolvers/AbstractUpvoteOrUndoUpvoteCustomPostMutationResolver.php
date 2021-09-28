<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

abstract class AbstractUpvoteOrUndoUpvoteCustomPostMutationResolver extends AbstractCustomPostUpdateUserMetaValueMutationResolver
{
    protected function eligible($post)
    {
        $eligible = in_array($this->customPostTypeAPI->getCustomPostType($post), \PoP_SocialNetwork_Utils::getUpdownvotePostTypes());
        return $this->hooksAPI->applyFilters('GD_UpdownvoteUndoUpdownvotePost:eligible', $eligible, $post);
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, $form_data): void
    {
        parent::additionals($target_id, $form_data);
        $this->hooksAPI->doAction('gd_upvoteundoupvote_post', $target_id, $form_data);
    }
}
