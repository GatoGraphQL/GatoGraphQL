<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_ApplicationProcessors_Utils;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPSitesWassup\CustomPostLinkMutations\MutationResolvers\MutationResolverUtils;
use PoPSitesWassup\PostMutations\MutationResolvers\AbstractCreateUpdatePostMutationResolver;

abstract class AbstractCreateUpdatePostLinkMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    protected function getCategories(MutationDataProviderInterface $mutationDataProvider): ?array
    {
        $ret = parent::getCategories($mutationDataProvider);
        $ret[] = \POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
        return $ret;
    }

    protected function validateContent(array &$errors, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::validateContent($errors, $mutationDataProvider);
        MutationResolverUtils::validateContent($errors, $mutationDataProvider);
    }

    protected function additionals(string | int $post_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionals($post_id, $mutationDataProvider);

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_LINKACCESS, $mutationDataProvider->getArgumentValue('linkaccess'), true);
        }
    }
}
