<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\AbstractUpdateCustomPostMetaInputObjectTypeResolver;

abstract class AbstractUpdateCategoryTermMetaInputObjectTypeResolver extends AbstractUpdateCustomPostMetaInputObjectTypeResolver implements UpdateCategoryTermMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a category term\'s meta', 'custompostmeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category', 'custompostmeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
