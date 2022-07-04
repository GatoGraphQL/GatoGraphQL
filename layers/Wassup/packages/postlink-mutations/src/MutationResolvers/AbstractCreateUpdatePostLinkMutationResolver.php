<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataProviderInterface;
use PoP_ApplicationProcessors_Utils;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPSitesWassup\CustomPostLinkMutations\MutationResolvers\MutationResolverUtils;
use PoPSitesWassup\PostMutations\MutationResolvers\AbstractCreateUpdatePostMutationResolver;

abstract class AbstractCreateUpdatePostLinkMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    protected function getCategories(FieldDataProviderInterface $fieldDataProvider): ?array
    {
        $ret = parent::getCategories($fieldDataProvider);
        $ret[] = \POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
        return $ret;
    }

    protected function validateContent(array &$errors, FieldDataProviderInterface $fieldDataProvider): void
    {
        parent::validateContent($errors, $fieldDataProvider);
        MutationResolverUtils::validateContent($errors, $fieldDataProvider);
    }

    protected function additionals(string | int $post_id, FieldDataProviderInterface $fieldDataProvider): void
    {
        parent::additionals($post_id, $fieldDataProvider);

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_LINKACCESS, $fieldDataProvider->get('linkaccess'), true);
        }
    }
}
