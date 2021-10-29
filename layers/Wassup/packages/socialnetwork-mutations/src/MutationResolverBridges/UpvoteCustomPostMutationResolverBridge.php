<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UpvoteCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver = null;

    public function setUpvoteCustomPostMutationResolver(UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver): void
    {
        $this->upvoteCustomPostMutationResolver = $upvoteCustomPostMutationResolver;
    }
    protected function getUpvoteCustomPostMutationResolver(): UpvoteCustomPostMutationResolver
    {
        return $this->upvoteCustomPostMutationResolver ??= $this->instanceManager->getInstance(UpvoteCustomPostMutationResolver::class);
    }

    //#[Required]
    final public function autowireUpvoteCustomPostMutationResolverBridge(
        UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver,
    ): void {
        $this->upvoteCustomPostMutationResolver = $upvoteCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpvoteCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have up-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
