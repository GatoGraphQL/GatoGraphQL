<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\DownvoteCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class DownvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    protected DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver;

    #[Required]
    final public function autowireDownvoteCustomPostMutationResolverBridge(
        DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver,
    ): void {
        $this->downvoteCustomPostMutationResolver = $downvoteCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->downvoteCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have down-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
