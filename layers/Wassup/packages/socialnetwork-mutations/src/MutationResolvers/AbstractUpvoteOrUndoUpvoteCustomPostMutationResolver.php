<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP_SocialNetwork_Utils;
use PoP\Root\App;

abstract class AbstractUpvoteOrUndoUpvoteCustomPostMutationResolver extends AbstractCustomPostUpdateUserMetaValueMutationResolver
{
    protected function eligible($post)
    {
        $eligible = in_array($this->getCustomPostTypeAPI()->getCustomPostType($post), PoP_SocialNetwork_Utils::getUpdownvotePostTypes());
        return App::applyFilters('GD_UpdownvoteUndoUpdownvotePost:eligible', $eligible, $post);
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionals($target_id, $mutationDataProvider);
        App::doAction('gd_upvoteundoupvote_post', $target_id, $mutationDataProvider);
    }
}
