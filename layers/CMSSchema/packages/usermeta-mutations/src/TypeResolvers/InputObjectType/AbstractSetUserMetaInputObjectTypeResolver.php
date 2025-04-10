<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractSetEntityMetaInputObjectTypeResolver;

abstract class AbstractSetUserMetaInputObjectTypeResolver extends AbstractSetEntityMetaInputObjectTypeResolver implements SetUserMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set entries on a user', 'usermeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the user', 'usermeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
