<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\HookNames;
use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractCreateMediaItemInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CreateMediaItemFromOneofInputObjectTypeResolver $createMediaItemFromOneofInputObjectTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setCreateMediaItemFromOneofInputObjectTypeResolver(CreateMediaItemFromOneofInputObjectTypeResolver $createMediaItemFromOneofInputObjectTypeResolver): void
    {
        $this->createMediaItemFromOneofInputObjectTypeResolver = $createMediaItemFromOneofInputObjectTypeResolver;
    }
    final protected function getCreateMediaItemFromOneofInputObjectTypeResolver(): CreateMediaItemFromOneofInputObjectTypeResolver
    {
        if ($this->createMediaItemFromOneofInputObjectTypeResolver === null) {
            /** @var CreateMediaItemFromOneofInputObjectTypeResolver */
            $createMediaItemFromOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CreateMediaItemFromOneofInputObjectTypeResolver::class);
            $this->createMediaItemFromOneofInputObjectTypeResolver = $createMediaItemFromOneofInputObjectTypeResolver;
        }
        return $this->createMediaItemFromOneofInputObjectTypeResolver;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        $inputFieldNameTypeResolvers = [
            MutationInputProperties::FROM => $this->getCreateMediaItemFromOneofInputObjectTypeResolver(),
            MutationInputProperties::AUTHOR_ID => $this->getIDScalarTypeResolver(),
            MutationInputProperties::TITLE => $this->getStringScalarTypeResolver(),
            MutationInputProperties::SLUG => $this->getStringScalarTypeResolver(),
            MutationInputProperties::CAPTION => $this->getStringScalarTypeResolver(),
            MutationInputProperties::DESCRIPTION => $this->getStringScalarTypeResolver(),
            MutationInputProperties::ALT_TEXT => $this->getStringScalarTypeResolver(),
        ];

        // Inject custom post ID, etc
        $inputFieldNameTypeResolvers = App::applyFilters(
            HookNames::CREATE_MEDIA_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $inputFieldNameTypeResolvers,
            $this,
        );

        return $inputFieldNameTypeResolvers;
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        $inputFieldDescription = match ($inputFieldName) {
            MutationInputProperties::FROM => $this->__('Source for the file', 'media-mutations'),
            MutationInputProperties::AUTHOR_ID => $this->__('The ID of the author', 'media-mutations'),
            MutationInputProperties::TITLE => $this->__('Attachment title', 'media-mutations'),
            MutationInputProperties::SLUG => $this->__('Attachment slug', 'media-mutations'),
            MutationInputProperties::CAPTION => $this->__('Attachment caption', 'media-mutations'),
            MutationInputProperties::DESCRIPTION => $this->__('Attachment description', 'media-mutations'),
            MutationInputProperties::ALT_TEXT => $this->__('Image alternative information', 'media-mutations'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };

        // Inject custom post ID, etc
        $inputFieldDescription = App::applyFilters(
            HookNames::CREATE_MEDIA_ITEM_INPUT_FIELD_DESCRIPTION,
            $inputFieldDescription,
            $inputFieldName,
            $this,
        );

        return $inputFieldDescription;
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        $inputFieldTypeModifiers = match ($inputFieldName) {
            MutationInputProperties::FROM => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };

        // Inject custom post ID, etc
        $inputFieldTypeModifiers = App::applyFilters(
            HookNames::CREATE_MEDIA_ITEM_INPUT_FIELD_TYPE_MODIFIERS,
            $inputFieldTypeModifiers,
            $inputFieldName,
            $this,
        );

        return $inputFieldTypeModifiers;
    }
}
