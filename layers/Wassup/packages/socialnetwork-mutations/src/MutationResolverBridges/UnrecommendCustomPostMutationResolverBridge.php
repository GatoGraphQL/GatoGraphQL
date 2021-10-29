<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnrecommendCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UnrecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver = null;

    public function setUnrecommendCustomPostMutationResolver(UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver): void
    {
        $this->unrecommendCustomPostMutationResolver = $unrecommendCustomPostMutationResolver;
    }
    protected function getUnrecommendCustomPostMutationResolver(): UnrecommendCustomPostMutationResolver
    {
        return $this->unrecommendCustomPostMutationResolver ??= $this->instanceManager->getInstance(UnrecommendCustomPostMutationResolver::class);
    }

    //#[Required]
    final public function autowireUnrecommendCustomPostMutationResolverBridge(
        UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver,
    ): void {
        $this->unrecommendCustomPostMutationResolver = $unrecommendCustomPostMutationResolver;
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
            $this->translationAPI->__('You have stopped recommending <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
