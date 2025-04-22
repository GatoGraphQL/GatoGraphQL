<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType\AbstractDeleteEntityMetaInputObjectTypeResolver;

abstract class AbstractDeleteUserMetaInputObjectTypeResolver extends AbstractDeleteEntityMetaInputObjectTypeResolver implements DeleteUserMetaInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a user\'s meta entry', 'usermeta-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the user', 'usermeta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
