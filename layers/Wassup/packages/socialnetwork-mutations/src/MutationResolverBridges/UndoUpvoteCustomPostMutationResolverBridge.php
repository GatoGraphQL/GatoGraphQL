<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UndoUpvoteCustomPostMutationResolver;

class UndoUpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    protected UndoUpvoteCustomPostMutationResolver $undoUpvoteCustomPostMutationResolver;

    #[Required]
    public function autowireUndoUpvoteCustomPostMutationResolverBridge(
        UndoUpvoteCustomPostMutationResolver $undoUpvoteCustomPostMutationResolver,
    ): void {
        $this->undoUpvoteCustomPostMutationResolver = $undoUpvoteCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->undoUpvoteCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have stopped up-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
