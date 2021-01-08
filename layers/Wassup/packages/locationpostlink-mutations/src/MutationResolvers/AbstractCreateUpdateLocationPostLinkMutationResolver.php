<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostLinkMutations\MutationResolvers;

use PoPSitesWassup\LocationPostMutations\MutationResolvers\AbstractCreateUpdateLocationPostMutationResolver;
use PoPSitesWassup\CustomPostLinkMutations\MutationResolvers\MutationResolverUtils;

abstract class AbstractCreateUpdateLocationPostLinkMutationResolver extends AbstractCreateUpdateLocationPostMutationResolver
{
    protected function getCategories(array $form_data): ?array
    {
        $ret = parent::getCategories($form_data);
        if (defined('POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS') && POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS) {
            $ret[] = POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS;
        }
        return $ret;
    }

    /**
     * Function below was copied from class GD_CreateUpdate_PostLink
     */
    protected function validateContent(array &$errors, array $form_data): void
    {
        parent::validateContent($errors, $form_data);
        MutationResolverUtils::validateContent($errors, $form_data);
    }
}
