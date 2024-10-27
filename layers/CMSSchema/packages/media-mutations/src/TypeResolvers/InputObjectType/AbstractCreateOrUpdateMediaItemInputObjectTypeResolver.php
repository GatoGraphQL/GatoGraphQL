<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\MediaCRUDHookNames;
use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractCreateOrUpdateMediaItemInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements CreateMediaItemInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CreateMediaItemFromOneofInputObjectTypeResolver $createMediaItemFromOneofInputObjectTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
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
        $inputFieldNameTypeResolvers = array_merge(
            $this->addMediaItemInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            $this->canUploadAttachment() ? [
                MutationInputProperties::FROM => $this->getCreateMediaItemFromOneofInputObjectTypeResolver(),
            ] : [],
            [
                MutationInputProperties::AUTHOR_ID => $this->getIDScalarTypeResolver(),
                MutationInputProperties::TITLE => $this->getStringScalarTypeResolver(),
                MutationInputProperties::SLUG => $this->getStringScalarTypeResolver(),
                MutationInputProperties::CAPTION => $this->getStringScalarTypeResolver(),
                MutationInputProperties::DESCRIPTION => $this->getStringScalarTypeResolver(),
                MutationInputProperties::ALT_TEXT => $this->getStringScalarTypeResolver(),
                MutationInputProperties::MIME_TYPE => $this->getStringScalarTypeResolver(),
            ]
        );

        // Inject custom post ID, etc
        $inputFieldNameTypeResolvers = App::applyFilters(
            MediaCRUDHookNames::CREATE_OR_UPDATE_MEDIA_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $inputFieldNameTypeResolvers,
            $this,
        );

        return $inputFieldNameTypeResolvers;
    }

    abstract protected function addMediaItemInputField(): bool;

    abstract protected function canUploadAttachment(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        $inputFieldDescription = match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('Media item ID', 'media-mutations'),
            MutationInputProperties::FROM => $this->__('Source for the file', 'media-mutations'),
            MutationInputProperties::AUTHOR_ID => $this->__('The ID of the author', 'media-mutations'),
            MutationInputProperties::TITLE => $this->__('Attachment title', 'media-mutations'),
            MutationInputProperties::SLUG => $this->__('Attachment slug', 'media-mutations'),
            MutationInputProperties::CAPTION => $this->__('Attachment caption', 'media-mutations'),
            MutationInputProperties::DESCRIPTION => $this->__('Attachment description', 'media-mutations'),
            MutationInputProperties::ALT_TEXT => $this->__('Image alternative information', 'media-mutations'),
            MutationInputProperties::MIME_TYPE => $this->__('Mime type to use for the attachment, when this information can\'t be deduced from the filename (because it has no extension)', 'media-mutations'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };

        // Inject custom post ID, etc
        $inputFieldDescription = App::applyFilters(
            MediaCRUDHookNames::CREATE_OR_UPDATE_MEDIA_ITEM_INPUT_FIELD_DESCRIPTION,
            $inputFieldDescription,
            $inputFieldName,
            $this,
        );

        return $inputFieldDescription;
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        $inputFieldTypeModifiers = match ($inputFieldName) {
            MutationInputProperties::ID => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::FROM => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };

        // Inject custom post ID, etc
        $inputFieldTypeModifiers = App::applyFilters(
            MediaCRUDHookNames::CREATE_OR_UPDATE_MEDIA_ITEM_INPUT_FIELD_TYPE_MODIFIERS,
            $inputFieldTypeModifiers,
            $inputFieldName,
            $this,
        );

        return $inputFieldTypeModifiers;
    }
}
