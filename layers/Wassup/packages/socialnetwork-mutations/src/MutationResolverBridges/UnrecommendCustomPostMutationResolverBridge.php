<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnrecommendCustomPostMutationResolver;

class UnrecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    protected UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver;

    #[Required]
    public function autowireUnrecommendCustomPostMutationResolverBridge(
        UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver,
    ): void {
        $this->unrecommendCustomPostMutationResolver = $unrecommendCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->unrecommendCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have stopped recommending <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
