<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnsubscribeFromTagMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UnsubscribeFromTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    protected ?UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver = null;
    protected ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    public function setUnsubscribeFromTagMutationResolver(UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver): void
    {
        $this->unsubscribeFromTagMutationResolver = $unsubscribeFromTagMutationResolver;
    }
    protected function getUnsubscribeFromTagMutationResolver(): UnsubscribeFromTagMutationResolver
    {
        return $this->unsubscribeFromTagMutationResolver ??= $this->instanceManager->getInstance(UnsubscribeFromTagMutationResolver::class);
    }
    public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    //#[Required]
    final public function autowireUnsubscribeFromTagMutationResolverBridge(
        UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver,
        PostTagTypeAPIInterface $postTagTypeAPI,
    ): void {
        $this->unsubscribeFromTagMutationResolver = $unsubscribeFromTagMutationResolver;
        $this->postTagTypeAPI = $postTagTypeAPI;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUnsubscribeFromTagMutationResolver();
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
            $this->translationAPI->__('You have unsubscribed from <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}
