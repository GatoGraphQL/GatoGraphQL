<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UndoUpvoteCustomPostMutationResolver;

class UndoUpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?UndoUpvoteCustomPostMutationResolver $undoUpvoteCustomPostMutationResolver = null;

    final public function setUndoUpvoteCustomPostMutationResolver(UndoUpvoteCustomPostMutationResolver $undoUpvoteCustomPostMutationResolver): void
    {
        $this->undoUpvoteCustomPostMutationResolver = $undoUpvoteCustomPostMutationResolver;
    }
    final protected function getUndoUpvoteCustomPostMutationResolver(): UndoUpvoteCustomPostMutationResolver
    {
        return $this->undoUpvoteCustomPostMutationResolver ??= $this->instanceManager->getInstance(UndoUpvoteCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUndoUpvoteCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->__('You have stopped up-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
