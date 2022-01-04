<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\TypeResolvers\InputObjectType;

use PoPSchema\CommentMeta\Component;
use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class CommentMetaQueryInputObjectTypeResolver extends AbstractMetaQueryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentMetaQueryInput';
    }

    /**
     * @return string[]
     */
    protected function getAllowOrDenyEntries(): array
    {
        return ComponentConfiguration::getCommentMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        return ComponentConfiguration::getCommentMetaBehavior();
    }
}
