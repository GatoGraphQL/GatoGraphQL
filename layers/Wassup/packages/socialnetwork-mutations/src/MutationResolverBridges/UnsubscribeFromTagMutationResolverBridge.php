<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnsubscribeFromTagMutationResolver;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class UnsubscribeFromTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return UnsubscribeFromTagMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
        $tag = $postTagTypeAPI->getTag($result_id);
        return sprintf(
            $this->translationAPI->__('You have unsubscribed from <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}
