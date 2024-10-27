<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Comments\Constants\CommentOrderBy;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class CommentSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?CommentOrderByEnumTypeResolver $customPostSortByEnumTypeResolver = null;

    final protected function getCommentOrderByEnumTypeResolver(): CommentOrderByEnumTypeResolver
    {
        if ($this->customPostSortByEnumTypeResolver === null) {
            /** @var CommentOrderByEnumTypeResolver */
            $customPostSortByEnumTypeResolver = $this->instanceManager->getInstance(CommentOrderByEnumTypeResolver::class);
            $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
        }
        return $this->customPostSortByEnumTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'CommentSortInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
