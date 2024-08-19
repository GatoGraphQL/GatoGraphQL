<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\HookNames;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractUpdateMediaItemInputObjectTypeResolver extends AbstractCreateOrUpdateMediaItemInputObjectTypeResolver implements UpdateMediaItemInputObjectTypeResolverInterface
{
    protected function addMediaItemInputField(): bool
    {
        return true;
    }

    protected function canUploadAttachment(): bool
    {
        return false;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return App::applyFilters(
            HookNames::UPDATE_MEDIA_ITEM_INPUT_FIELD_NAME_TYPE_RESOLVERS,
            parent::getInputFieldNameTypeResolvers(),
            $this,
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return App::applyFilters(
            HookNames::UPDATE_MEDIA_ITEM_INPUT_FIELD_DESCRIPTION,
            parent::getInputFieldDescription($inputFieldName),
            $inputFieldName,
            $this,
        );
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return App::applyFilters(
            HookNames::UPDATE_MEDIA_ITEM_INPUT_FIELD_TYPE_MODIFIERS,
            parent::getInputFieldTypeModifiers($inputFieldName),
            $inputFieldName,
            $this,
        );
    }
}
