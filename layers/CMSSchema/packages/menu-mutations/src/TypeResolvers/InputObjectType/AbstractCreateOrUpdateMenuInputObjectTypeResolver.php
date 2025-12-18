<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MenuMutations\Constants\MenuCRUDHookNames;
use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractCreateOrUpdateMenuInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements CreateMenuInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CreateMenuFromOneofInputObjectTypeResolver $createMenuFromOneofInputObjectTypeResolver = null;
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

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
    final protected function getCreateMenuFromOneofInputObjectTypeResolver(): CreateMenuFromOneofInputObjectTypeResolver
    {
        if ($this->createMenuFromOneofInputObjectTypeResolver === null) {
            /** @var CreateMenuFromOneofInputObjectTypeResolver */
            $createMenuFromOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CreateMenuFromOneofInputObjectTypeResolver::class);
            $this->createMenuFromOneofInputObjectTypeResolver = $createMenuFromOneofInputObjectTypeResolver;
        }
        return $this->createMenuFromOneofInputObjectTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        if ($this->dateScalarTypeResolver === null) {
            /** @var DateScalarTypeResolver */
            $dateScalarTypeResolver = $this->instanceManager->getInstance(DateScalarTypeResolver::class);
            $this->dateScalarTypeResolver = $dateScalarTypeResolver;
        }
        return $this->dateScalarTypeResolver;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        $inputFieldNameTypeResolvers = array_merge(
            $this->addMenuInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            $this->canUploadAttachment() ? [
                MutationInputProperties::FROM => $this->getCreateMenuFromOneofInputObjectTypeResolver(),
            ] : [],
            [
                MutationInputProperties::AUTHOR_ID => $this->getIDScalarTypeResolver(),
                MutationInputProperties::TITLE => $this->getStringScalarTypeResolver(),
                MutationInputProperties::SLUG => $this->getStringScalarTypeResolver(),
                MutationInputProperties::CAPTION => $this->getStringScalarTypeResolver(),
                MutationInputProperties::DESCRIPTION => $this->getStringScalarTypeResolver(),
                MutationInputProperties::ALT_TEXT => $this->getStringScalarTypeResolver(),
                MutationInputProperties::MIME_TYPE => $this->getStringScalarTypeResolver(),
                MutationInputProperties::DATE => $this->getDateScalarTypeResolver(),
                MutationInputProperties::GMT_DATE => $this->getDateScalarTypeResolver(),
            ]
        );

        // Inject custom post ID, etc
        $inputFieldNameTypeResolvers = App::applyFilters(
            MenuCRUDHookNames::CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $inputFieldNameTypeResolvers,
            $this,
        );

        return $inputFieldNameTypeResolvers;
    }

    abstract protected function addMenuInputField(): bool;

    abstract protected function canUploadAttachment(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        $inputFieldDescription = match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('Menu item ID', 'menu-mutations'),
            MutationInputProperties::FROM => $this->__('Source for the file', 'menu-mutations'),
            MutationInputProperties::AUTHOR_ID => $this->__('The ID of the author', 'menu-mutations'),
            MutationInputProperties::TITLE => $this->__('Attachment title', 'menu-mutations'),
            MutationInputProperties::SLUG => $this->__('Attachment slug', 'menu-mutations'),
            MutationInputProperties::CAPTION => $this->__('Attachment caption', 'menu-mutations'),
            MutationInputProperties::DESCRIPTION => $this->__('Attachment description', 'menu-mutations'),
            MutationInputProperties::ALT_TEXT => $this->__('Image alternative information', 'menu-mutations'),
            MutationInputProperties::MIME_TYPE => $this->__('Mime type to use for the attachment, when this information can\'t be deduced from the filename (because it has no extension)', 'menu-mutations'),
            MutationInputProperties::DATE => $this->__('Date to use for the attachment', 'menu-mutations'),
            MutationInputProperties::GMT_DATE => $this->__('GMT date to use for the attachment', 'menu-mutations'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };

        // Inject custom post ID, etc
        $inputFieldDescription = App::applyFilters(
            MenuCRUDHookNames::CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_DESCRIPTION,
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
            MenuCRUDHookNames::CREATE_OR_UPDATE_MENU_ITEM_INPUT_FIELD_TYPE_MODIFIERS,
            $inputFieldTypeModifiers,
            $inputFieldName,
            $this,
        );

        return $inputFieldTypeModifiers;
    }
}
