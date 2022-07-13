<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP_ApplicationProcessors_Utils;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPSitesWassup\CustomPostLinkMutations\MutationResolvers\MutationResolverUtils;
use PoPSitesWassup\PostMutations\MutationResolvers\AbstractCreateUpdatePostMutationResolver;

abstract class AbstractCreateUpdatePostLinkMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    protected function getCategories(FieldDataAccessorInterface $fieldDataAccessor): ?array
    {
        $ret = parent::getCategories($fieldDataAccessor);
        $ret[] = \POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
        return $ret;
    }

    protected function validateContent(array &$errors, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::validateContent($errors, $fieldDataAccessor);
        MutationResolverUtils::validateContent($errors, $fieldDataAccessor);
    }

    protected function additionals(string|int $post_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($post_id, $fieldDataAccessor);

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_LINKACCESS, $fieldDataAccessor->getValue('linkaccess'), true);
        }
    }
}
