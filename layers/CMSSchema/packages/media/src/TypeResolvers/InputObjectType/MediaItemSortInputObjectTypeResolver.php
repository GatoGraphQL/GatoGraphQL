<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Media\Constants\MediaItemOrderBy;
use PoPCMSSchema\Media\TypeResolvers\EnumType\MediaItemOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class MediaItemSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?MediaItemOrderByEnumTypeResolver $customPostSortByEnumTypeResolver = null;

    final protected function getMediaItemOrderByEnumTypeResolver(): MediaItemOrderByEnumTypeResolver
    {
        if ($this->customPostSortByEnumTypeResolver === null) {
            /** @var MediaItemOrderByEnumTypeResolver */
            $customPostSortByEnumTypeResolver = $this->instanceManager->getInstance(MediaItemOrderByEnumTypeResolver::class);
            $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
        }
        return $this->customPostSortByEnumTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'MediaItemSortInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
