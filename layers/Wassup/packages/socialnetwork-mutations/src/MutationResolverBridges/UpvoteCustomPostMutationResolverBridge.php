<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UpvoteCustomPostMutationResolver;

class UpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver = null;

    final public function setUpvoteCustomPostMutationResolver(UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver): void
    {
        $this->upvoteCustomPostMutationResolver = $upvoteCustomPostMutationResolver;
    }
    final protected function getUpvoteCustomPostMutationResolver(): UpvoteCustomPostMutationResolver
    {
        if ($this->upvoteCustomPostMutationResolver === null) {
            /** @var UpvoteCustomPostMutationResolver */
            $upvoteCustomPostMutationResolver = $this->instanceManager->getInstance(UpvoteCustomPostMutationResolver::class);
            $this->upvoteCustomPostMutationResolver = $upvoteCustomPostMutationResolver;
        }
        return $this->upvoteCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpvoteCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string|int $result_id): ?string
    {
        return sprintf(
            $this->__('You have up-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
