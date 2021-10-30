<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\DownvoteCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class DownvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    private ?DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver = null;

    public function setDownvoteCustomPostMutationResolver(DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver): void
    {
        $this->downvoteCustomPostMutationResolver = $downvoteCustomPostMutationResolver;
    }
    protected function getDownvoteCustomPostMutationResolver(): DownvoteCustomPostMutationResolver
    {
        return $this->downvoteCustomPostMutationResolver ??= $this->instanceManager->getInstance(DownvoteCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getDownvoteCustomPostMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->getTranslationAPI()->__('You have down-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getCustomPostTypeAPI()->getTitle($result_id)
        );
    }
}
