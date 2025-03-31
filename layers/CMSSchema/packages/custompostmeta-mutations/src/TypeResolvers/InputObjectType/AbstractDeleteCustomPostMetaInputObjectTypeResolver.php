<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType\AbstractDeleteCustomPostMetaInputObjectTypeResolver;

abstract class AbstractDeleteCustomPostMetaInputObjectTypeResolver extends AbstractDeleteCustomPostMetaInputObjectTypeResolver implements DeleteCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a custom post\'s meta entry', 'custompostmeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the custom post', 'custompostmeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
