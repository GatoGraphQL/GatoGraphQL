<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\InputObjectType;

use PoPSchema\Posts\Constants\PostOrderBy;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class PostSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?CustomPostOrderByEnumTypeResolver $customPostSortByEnumTypeResolver = null;

    final public function setCustomPostOrderByEnumTypeResolver(CustomPostOrderByEnumTypeResolver $customPostSortByEnumTypeResolver): void
    {
        $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
    }
    final protected function getCustomPostOrderByEnumTypeResolver(): CustomPostOrderByEnumTypeResolver
    {
        return $this->customPostSortByEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostOrderByEnumTypeResolver::class);
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
                'by' => $this->getCustomPostOrderByEnumTypeResolver(),
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
