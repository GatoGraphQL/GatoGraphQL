<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnrecommendCustomPostMutationResolver;

class UnrecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    public function getMutationResolver(): MutationResolverInterface
    {
        return UnrecommendCustomPostMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return sprintf(
            $this->translationAPI->__('You have stopped recommending <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $customPostTypeAPI->getTitle($result_id)
        );
    }
}
