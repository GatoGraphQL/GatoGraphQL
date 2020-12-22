<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnsubscribeFromTagMutationResolver;

class UnsubscribeFromTagMutationResolverBridge extends AbstractTagUpdateUserMetaValueMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UnsubscribeFromTagMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    /**
     * @param mixed $result_id Maybe an int, maybe a string
     */
    public function getSuccessString($result_id): ?string
    {
        $posttagapi = \PoPSchema\PostTags\FunctionAPIFactory::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        $tag = $posttagapi->getTag($result_id);
        return sprintf(
            TranslationAPIFacade::getInstance()->__('You have unsubscribed from <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $applicationtaxonomyapi->getTagSymbolName($tag)
        );
    }
}

