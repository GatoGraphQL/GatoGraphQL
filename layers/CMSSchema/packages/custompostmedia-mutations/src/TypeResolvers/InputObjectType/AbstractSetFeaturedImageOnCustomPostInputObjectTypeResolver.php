<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByOneofInputObjectTypeResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

abstract class AbstractSetFeaturedImageOnCustomPostInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?MediaItemByOneofInputObjectTypeResolver $mediaItemByOneofInputObjectTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getMediaItemByOneofInputObjectTypeResolver(): MediaItemByOneofInputObjectTypeResolver
    {
        if ($this->mediaItemByOneofInputObjectTypeResolver === null) {
            /** @var MediaItemByOneofInputObjectTypeResolver */
            $mediaItemByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(MediaItemByOneofInputObjectTypeResolver::class);
            $this->mediaItemByOneofInputObjectTypeResolver = $mediaItemByOneofInputObjectTypeResolver;
        }
        return $this->mediaItemByOneofInputObjectTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set the featured image on a custom post', 'custompostmedia-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            [
                MutationInputProperties::MEDIAITEM_BY => $this->getMediaItemByOneofInputObjectTypeResolver(),
            ],
            $this->addCustomPostInputField() ? [
                MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            ] : [],
        );
    }

    abstract protected function addCustomPostInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::CUSTOMPOST_ID => $this->__('The ID of the custom post', 'custompostmedia-mutations'),
            MutationInputProperties::MEDIAITEM_BY => $this->__('The image to set as featured', 'custompostmedia-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::MEDIAITEM_BY,
            MutationInputProperties::CUSTOMPOST_ID
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
