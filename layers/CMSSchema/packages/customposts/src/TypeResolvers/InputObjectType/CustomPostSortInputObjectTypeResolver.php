<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\Constants\CustomPostOrderBy;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class CustomPostSortInputObjectTypeResolver extends SortInputObjectTypeResolver
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
        return 'CustomPostSortInput';
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

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'by' => CustomPostOrderBy::DATE,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
