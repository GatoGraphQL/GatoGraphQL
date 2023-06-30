<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\DownvoteCustomPostMutationResolver;

class DownvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver = null;

    final public function setDownvoteCustomPostMutationResolver(DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver): void
    {
        $this->downvoteCustomPostMutationResolver = $downvoteCustomPostMutationResolver;
    }
    final protected function getDownvoteCustomPostMutationResolver(): DownvoteCustomPostMutationResolver
    {
        if ($this->downvoteCustomPostMutationResolver === null) {
            /** @var DownvoteCustomPostMutationResolver */
            $downvoteCustomPostMutationResolver = $this->instanceManager->getInstance(DownvoteCustomPostMutationResolver::class);
            $this->downvoteCustomPostMutationResolver = $downvoteCustomPostMutationResolver;
        }
        return $this->downvoteCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getDownvoteCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string|int $result_id): ?string
    {
        return sprintf(
            $this->__('You have down-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
