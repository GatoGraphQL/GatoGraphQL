<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Users\Constants\InputProperties;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserByOneofInputObjectTypeResolver;

class AuthorByOneofInputObjectTypeResolver extends UserByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'AuthorByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the custom post author', 'custompost-user-mutations');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->__('Provide author by ID', 'custompost-user-mutations'),
            InputProperties::USERNAME => $this->__('Provide author by username', 'custompost-user-mutations'),
            InputProperties::EMAIL => $this->__('Provide author by email', 'custompost-user-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
    
    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }
}
