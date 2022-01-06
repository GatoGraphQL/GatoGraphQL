<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UndoDownvoteCustomPostMutationResolver;

class UndoDownvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?UndoDownvoteCustomPostMutationResolver $undoDownvoteCustomPostMutationResolver = null;

    final public function setUndoDownvoteCustomPostMutationResolver(UndoDownvoteCustomPostMutationResolver $undoDownvoteCustomPostMutationResolver): void
    {
        $this->undoDownvoteCustomPostMutationResolver = $undoDownvoteCustomPostMutationResolver;
    }
    final protected function getUndoDownvoteCustomPostMutationResolver(): UndoDownvoteCustomPostMutationResolver
    {
        return $this->undoDownvoteCustomPostMutationResolver ??= $this->instanceManager->getInstance(UndoDownvoteCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUndoDownvoteCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->__('You have stopped down-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
