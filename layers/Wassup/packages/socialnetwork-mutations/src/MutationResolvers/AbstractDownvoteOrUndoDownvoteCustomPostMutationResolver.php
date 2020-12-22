<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

abstract class AbstractDownvoteOrUndoDownvoteCustomPostMutationResolver extends AbstractCustomPostUpdateUserMetaValueMutationResolver
{
    protected function eligible($post)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $eligible = in_array($customPostTypeAPI->getCustomPostType($post), PoP_SocialNetwork_Utils::getUpdownvotePostTypes());
        return HooksAPIFacade::getInstance()->applyFilters('GD_UpdownvoteUndoUpdownvotePost:eligible', $eligible, $post);
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, $form_data)
    {
        parent::additionals($target_id, $form_data);
        HooksAPIFacade::getInstance()->doAction('gd_downvoteundodownvote_post', $target_id, $form_data);
    }
}
