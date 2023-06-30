<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\RecommendCustomPostMutationResolver;

class RecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?RecommendCustomPostMutationResolver $recommendCustomPostMutationResolver = null;

    final public function setRecommendCustomPostMutationResolver(RecommendCustomPostMutationResolver $recommendCustomPostMutationResolver): void
    {
        $this->recommendCustomPostMutationResolver = $recommendCustomPostMutationResolver;
    }
    final protected function getRecommendCustomPostMutationResolver(): RecommendCustomPostMutationResolver
    {
        if ($this->recommendCustomPostMutationResolver === null) {
            /** @var RecommendCustomPostMutationResolver */
            $recommendCustomPostMutationResolver = $this->instanceManager->getInstance(RecommendCustomPostMutationResolver::class);
            $this->recommendCustomPostMutationResolver = $recommendCustomPostMutationResolver;
        }
        return $this->recommendCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getRecommendCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string|int $result_id): ?string
    {
        return sprintf(
            $this->__('You have recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
