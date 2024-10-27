<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\AbstractCommentsFilterInputObjectTypeResolver;
use PoPWPSchema\CommentMeta\TypeResolvers\InputObjectType\CommentMetaQueryInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    private ?CommentMetaQueryInputObjectTypeResolver $commentMetaQueryInputObjectTypeResolver = null;

    final protected function getCommentMetaQueryInputObjectTypeResolver(): CommentMetaQueryInputObjectTypeResolver
    {
        if ($this->commentMetaQueryInputObjectTypeResolver === null) {
            /** @var CommentMetaQueryInputObjectTypeResolver */
            $commentMetaQueryInputObjectTypeResolver = $this->instanceManager->getInstance(CommentMetaQueryInputObjectTypeResolver::class);
            $this->commentMetaQueryInputObjectTypeResolver = $commentMetaQueryInputObjectTypeResolver;
        }
        return $this->commentMetaQueryInputObjectTypeResolver;
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getCommentMetaQueryInputObjectTypeResolver();
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractCommentsFilterInputObjectTypeResolver;
    }
}
