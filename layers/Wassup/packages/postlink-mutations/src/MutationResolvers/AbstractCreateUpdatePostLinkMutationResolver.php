<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_ApplicationProcessors_Utils;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPSitesWassup\CustomPostLinkMutations\MutationResolvers\MutationResolverUtils;
use PoPSitesWassup\PostMutations\MutationResolvers\AbstractCreateUpdatePostMutationResolver;

abstract class AbstractCreateUpdatePostLinkMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    protected function getCategories(WithArgumentsInterface $withArgumentsAST): ?array
    {
        $ret = parent::getCategories($withArgumentsAST);
        $ret[] = \POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
        return $ret;
    }

    protected function validateContent(array &$errors, WithArgumentsInterface $withArgumentsAST): void
    {
        parent::validateContent($errors, $withArgumentsAST);
        MutationResolverUtils::validateContent($errors, $withArgumentsAST);
    }

    protected function additionals(string | int $post_id, WithArgumentsInterface $withArgumentsAST): void
    {
        parent::additionals($post_id, $withArgumentsAST);

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_LINKACCESS, $withArgumentsAST->getArgumentValue('linkaccess'), true);
        }
    }
}
