<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\SubscribeToTagMutationResolver;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

class SubscribeToTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    protected SubscribeToTagMutationResolver $subscribeToTagMutationResolver;
    protected PostTagTypeAPIInterface $postTagTypeAPI;

    #[Required]
    public function autowireSubscribeToTagMutationResolverBridge(
        SubscribeToTagMutationResolver $subscribeToTagMutationResolver,
        PostTagTypeAPIInterface $postTagTypeAPI,
    ): void {
        $this->subscribeToTagMutationResolver = $subscribeToTagMutationResolver;
        $this->postTagTypeAPI = $postTagTypeAPI;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->subscribeToTagMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
        $tag = $this->postTagTypeAPI->getTag($result_id);
        return sprintf(
            $this->translationAPI->__('You have subscribed to <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}
