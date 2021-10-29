<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\RecommendCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?RecommendCustomPostMutationResolver $recommendCustomPostMutationResolver = null;

    public function setRecommendCustomPostMutationResolver(RecommendCustomPostMutationResolver $recommendCustomPostMutationResolver): void
    {
        $this->recommendCustomPostMutationResolver = $recommendCustomPostMutationResolver;
    }
    protected function getRecommendCustomPostMutationResolver(): RecommendCustomPostMutationResolver
    {
        return $this->recommendCustomPostMutationResolver ??= $this->instanceManager->getInstance(RecommendCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getRecommendCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
