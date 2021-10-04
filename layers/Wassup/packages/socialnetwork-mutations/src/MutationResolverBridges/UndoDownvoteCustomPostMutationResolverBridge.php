<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UndoDownvoteCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UndoDownvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    protected UndoDownvoteCustomPostMutationResolver $undoDownvoteCustomPostMutationResolver;

    #[Required]
    final public function autowireUndoDownvoteCustomPostMutationResolverBridge(
        UndoDownvoteCustomPostMutationResolver $undoDownvoteCustomPostMutationResolver,
    ): void {
        $this->undoDownvoteCustomPostMutationResolver = $undoDownvoteCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->undoDownvoteCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have stopped down-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
