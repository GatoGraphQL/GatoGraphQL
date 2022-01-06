<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnrecommendCustomPostMutationResolver;

class UnrecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver = null;

    final public function setUnrecommendCustomPostMutationResolver(UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver): void
    {
        $this->unrecommendCustomPostMutationResolver = $unrecommendCustomPostMutationResolver;
    }
    final protected function getUnrecommendCustomPostMutationResolver(): UnrecommendCustomPostMutationResolver
    {
        return $this->unrecommendCustomPostMutationResolver ??= $this->instanceManager->getInstance(UnrecommendCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUnrecommendCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->__('You have stopped recommending <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
