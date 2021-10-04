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
    protected UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver;
    protected PostTagTypeAPIInterface $postTagTypeAPI;

    #[Required]
    final public function autowireUnsubscribeFromTagMutationResolverBridge(
        UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver,
        PostTagTypeAPIInterface $postTagTypeAPI,
    ): void {
        $this->unsubscribeFromTagMutationResolver = $unsubscribeFromTagMutationResolver;
        $this->postTagTypeAPI = $postTagTypeAPI;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->unsubscribeFromTagMutationResolver;
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
            $this->translationAPI->__('You have unsubscribed from <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}
