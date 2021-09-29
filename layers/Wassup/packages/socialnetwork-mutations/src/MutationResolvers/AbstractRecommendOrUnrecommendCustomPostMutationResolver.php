<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Application\PostsFunctionAPIFactory;

abstract class AbstractRecommendOrUnrecommendCustomPostMutationResolver extends AbstractCustomPostUpdateUserMetaValueMutationResolver
{
    protected function eligible($post)
    {
        $cmsapplicationpostsapi = PostsFunctionAPIFactory::getInstance();
        $eligible = in_array($this->customPostTypeAPI->getCustomPostType($post), $cmsapplicationpostsapi->getAllcontentPostTypes());
        return $this->hooksAPI->applyFilters('GD_RecommendUnrecommendPost:eligible', $eligible, $post);
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, $form_data): void
    {
        parent::additionals($target_id, $form_data);
        $this->hooksAPI->doAction('gd_recommendunrecommend_post', $target_id, $form_data);
    }
}
