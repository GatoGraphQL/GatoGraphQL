<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\SubscribeToTagMutationResolver;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class SubscribeToTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    public function __construct(
        protected \PoP\Hooks\HooksAPIInterface $hooksAPI,
        protected \PoP\Translation\TranslationAPIInterface $translationAPI,
        protected \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        protected \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected SubscribeToTagMutationResolver $SubscribeToTagMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
        );
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->SubscribeToTagMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $tag = $postTagTypeAPI->getTag($result_id);
        return sprintf(
            $this->translationAPI->__('You have subscribed to <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}
