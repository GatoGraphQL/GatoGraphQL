<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnsubscribeFromTagMutationResolver;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

class UnsubscribeFromTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    protected UnsubscribeFromTagMutationResolver $unsubscribeFromTagMutationResolver;
    protected PostTagTypeAPIInterface $postTagTypeAPI;

    #[Required]
    public function autowireUnsubscribeFromTagMutationResolverBridge(
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
