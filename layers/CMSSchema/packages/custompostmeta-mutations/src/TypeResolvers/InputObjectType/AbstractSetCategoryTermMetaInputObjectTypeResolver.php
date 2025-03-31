<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\AbstractSetCustomPostMetaInputObjectTypeResolver;

abstract class AbstractSetCustomPostMetaInputObjectTypeResolver extends AbstractSetCustomPostMetaInputObjectTypeResolver implements SetCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set a category term\'s meta entries', 'custompostmeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category', 'custompostmeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
