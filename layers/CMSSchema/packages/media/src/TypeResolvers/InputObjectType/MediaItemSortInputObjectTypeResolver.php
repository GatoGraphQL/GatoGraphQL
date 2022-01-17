<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

use PoPCMSSchema\Media\Constants\MediaItemOrderBy;
use PoPCMSSchema\Media\TypeResolvers\EnumType\MediaItemOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class MediaItemSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?MediaItemOrderByEnumTypeResolver $customPostSortByEnumTypeResolver = null;

    final public function setMediaItemOrderByEnumTypeResolver(MediaItemOrderByEnumTypeResolver $customPostSortByEnumTypeResolver): void
    {
        $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
    }
    final protected function getMediaItemOrderByEnumTypeResolver(): MediaItemOrderByEnumTypeResolver
    {
        return $this->customPostSortByEnumTypeResolver ??= $this->instanceManager->getInstance(MediaItemOrderByEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MediaItemSortInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'by' => $this->getMediaItemOrderByEnumTypeResolver(),
            ]
        );
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'by' => MediaItemOrderBy::DATE,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
