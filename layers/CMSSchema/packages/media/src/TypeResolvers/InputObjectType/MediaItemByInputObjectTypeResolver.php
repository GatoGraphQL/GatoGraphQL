<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;

class MediaItemByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MediaItemByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the property and data to fetch a media item', 'media');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'id' => $this->getIDScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by media item ID', 'users'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'id' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_INCLUDE],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
