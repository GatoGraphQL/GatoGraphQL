<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MenuMutations\Constants\MenuCRUDHookNames;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractCreateMenuInputObjectTypeResolver extends AbstractCreateOrUpdateMenuInputObjectTypeResolver implements CreateMenuInputObjectTypeResolverInterface
{
    protected function addMenuInputField(): bool
    {
        return false;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return App::applyFilters(
            MenuCRUDHookNames::CREATE_MENU_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS,
            parent::getInputFieldNameTypeResolvers(),
            $this,
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return App::applyFilters(
            MenuCRUDHookNames::CREATE_MENU_ITEM_INPUT_FIELD_DESCRIPTION,
            parent::getInputFieldDescription($inputFieldName),
            $inputFieldName,
            $this,
        );
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return App::applyFilters(
            MenuCRUDHookNames::CREATE_MENU_ITEM_INPUT_FIELD_TYPE_MODIFIERS,
            parent::getInputFieldTypeModifiers($inputFieldName),
            $inputFieldName,
            $this,
        );
    }
}
