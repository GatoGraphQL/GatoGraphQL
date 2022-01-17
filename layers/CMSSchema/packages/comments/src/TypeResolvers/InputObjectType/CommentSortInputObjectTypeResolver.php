<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InputObjectType;

use PoPSchema\Comments\Constants\CommentOrderBy;
use PoPSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class CommentSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?CommentOrderByEnumTypeResolver $customPostSortByEnumTypeResolver = null;

    final public function setCommentOrderByEnumTypeResolver(CommentOrderByEnumTypeResolver $customPostSortByEnumTypeResolver): void
    {
        $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
    }
    final protected function getCommentOrderByEnumTypeResolver(): CommentOrderByEnumTypeResolver
    {
        return $this->customPostSortByEnumTypeResolver ??= $this->instanceManager->getInstance(CommentOrderByEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'CommentSortInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'by' => $this->getCommentOrderByEnumTypeResolver(),
            ]
        );
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'by' => CommentOrderBy::DATE,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
