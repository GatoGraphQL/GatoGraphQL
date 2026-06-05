<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractDeleteEntityMetaInputObjectTypeResolver;

abstract class AbstractDeleteCustomPostMetaInputObjectTypeResolver extends AbstractDeleteEntityMetaInputObjectTypeResolver implements DeleteCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a custom post\'s meta entry', 'gatographql');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the custom post', 'gatographql'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
