<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\SubscribeToTagMutationResolver;

class SubscribeToTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    private ?SubscribeToTagMutationResolver $subscribeToTagMutationResolver = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setSubscribeToTagMutationResolver(SubscribeToTagMutationResolver $subscribeToTagMutationResolver): void
    {
        $this->subscribeToTagMutationResolver = $subscribeToTagMutationResolver;
    }
    final protected function getSubscribeToTagMutationResolver(): SubscribeToTagMutationResolver
    {
        return $this->subscribeToTagMutationResolver ??= $this->instanceManager->getInstance(SubscribeToTagMutationResolver::class);
    }
    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getSubscribeToTagMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
        $tag = $this->getPostTagTypeAPI()->getTag($result_id);
        return sprintf(
            $this->__('You have subscribed to <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}
