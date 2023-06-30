<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnsubscribeFromTagMutationResolver;

class UnsubscribeFromTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    private ?UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setUnsubscribeFromTagMutationResolver(UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver): void
    {
        $this->unsubscribeFromTagMutationResolver = $unsubscribeFromTagMutationResolver;
    }
    final protected function getUnsubscribeFromTagMutationResolver(): UnsubscribeFromTagMutationResolver
    {
        if ($this->unsubscribeFromTagMutationResolver === null) {
            /** @var UnsubscribeFromTagMutationResolver */
            $unsubscribeFromTagMutationResolver = $this->instanceManager->getInstance(UnsubscribeFromTagMutationResolver::class);
            $this->unsubscribeFromTagMutationResolver = $unsubscribeFromTagMutationResolver;
        }
        return $this->unsubscribeFromTagMutationResolver;
    }
    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUnsubscribeFromTagMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string|int $result_id): ?string
    {
        $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
        $tag = $this->getPostTagTypeAPI()->getTag($result_id);
        return sprintf(
            $this->__('You have unsubscribed from <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}
