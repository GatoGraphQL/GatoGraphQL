<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\RecommendCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    protected RecommendCustomPostMutationResolver $recommendCustomPostMutationResolver;

    #[Required]
    final public function autowireRecommendCustomPostMutationResolverBridge(
        RecommendCustomPostMutationResolver $recommendCustomPostMutationResolver,
    ): void {
        $this->recommendCustomPostMutationResolver = $recommendCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->recommendCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
