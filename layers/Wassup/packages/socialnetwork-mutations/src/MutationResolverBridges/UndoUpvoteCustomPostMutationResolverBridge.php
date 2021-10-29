<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UndoUpvoteCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UndoUpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?UndoUpvoteCustomPostMutationResolver $undoUpvoteCustomPostMutationResolver = null;

    public function setUndoUpvoteCustomPostMutationResolver(UndoUpvoteCustomPostMutationResolver $undoUpvoteCustomPostMutationResolver): void
    {
        $this->undoUpvoteCustomPostMutationResolver = $undoUpvoteCustomPostMutationResolver;
    }
    protected function getUndoUpvoteCustomPostMutationResolver(): UndoUpvoteCustomPostMutationResolver
    {
        return $this->undoUpvoteCustomPostMutationResolver ??= $this->instanceManager->getInstance(UndoUpvoteCustomPostMutationResolver::class);
    }

    //#[Required]
    final public function autowireUndoUpvoteCustomPostMutationResolverBridge(
        UndoUpvoteCustomPostMutationResolver $undoUpvoteCustomPostMutationResolver,
    ): void {
        $this->undoUpvoteCustomPostMutationResolver = $undoUpvoteCustomPostMutationResolver;
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
            $this->translationAPI->__('You have stopped up-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
