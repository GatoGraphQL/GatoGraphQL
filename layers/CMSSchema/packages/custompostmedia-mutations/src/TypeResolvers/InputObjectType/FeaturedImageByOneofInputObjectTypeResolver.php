<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Media\Constants\InputProperties;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByOneofInputObjectTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;

class FeaturedImageByOneofInputObjectTypeResolver extends MediaItemByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'FeaturedImageByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the custom post\'s featured image', 'custompostmedia-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->__('Provide featured image by ID', 'custompostmedia-mutations'),
            InputProperties::SLUG => $this->__('Provide featured image by slug', 'custompostmedia-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return null;
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }

    protected function isOneOfInputPropertyNullable(
        string $propertyName
    ): bool {
        return match ($propertyName) {
            InputProperties::ID,
            InputProperties::SLUG
                => true,
            default
                => parent::isOneOfInputPropertyNullable($propertyName)
        };
    }
}
