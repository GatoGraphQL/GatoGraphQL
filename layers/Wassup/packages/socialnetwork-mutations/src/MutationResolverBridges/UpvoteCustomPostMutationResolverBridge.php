<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UpvoteCustomPostMutationResolver;

class UpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    protected UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver;

    #[Required]
    public function autowireUpvoteCustomPostMutationResolverBridge(
        UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver,
    ): void {
        $this->upvoteCustomPostMutationResolver = $upvoteCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->upvoteCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have up-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
