<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\InputObjectType;

use PoPSchema\Posts\Constants\PostOrderBy;
use PoPSchema\Posts\TypeResolvers\EnumType\PostOrderByEnumTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class PostSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?PostOrderByEnumTypeResolver $postSortByEnumTypeResolver = null;

    final public function setPostOrderByEnumTypeResolver(PostOrderByEnumTypeResolver $postSortByEnumTypeResolver): void
    {
        $this->postSortByEnumTypeResolver = $postSortByEnumTypeResolver;
    }
    final protected function getPostOrderByEnumTypeResolver(): PostOrderByEnumTypeResolver
    {
        return $this->postSortByEnumTypeResolver ??= $this->instanceManager->getInstance(PostOrderByEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'PostSortInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'by' => $this->getPostOrderByEnumTypeResolver(),
            ]
        );
    }

    public function getInputFieldDefaultValue(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'by' => PostOrderBy::DATE,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
