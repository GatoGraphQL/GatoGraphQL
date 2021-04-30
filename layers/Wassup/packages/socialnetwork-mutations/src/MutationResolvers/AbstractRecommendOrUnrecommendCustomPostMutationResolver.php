<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

abstract class AbstractRecommendOrUnrecommendCustomPostMutationResolver extends AbstractCustomPostUpdateUserMetaValueMutationResolver
{
    protected function eligible($post)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        $eligible = in_array($customPostTypeAPI->getCustomPostType($post), $cmsapplicationpostsapi->getAllcontentPostTypes());
        return $this->hooksAPI->applyFilters('GD_RecommendUnrecommendPost:eligible', $eligible, $post);
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, $form_data)
    {
        parent::additionals($target_id, $form_data);
        $this->hooksAPI->doAction('gd_recommendunrecommend_post', $target_id, $form_data);
    }
}
