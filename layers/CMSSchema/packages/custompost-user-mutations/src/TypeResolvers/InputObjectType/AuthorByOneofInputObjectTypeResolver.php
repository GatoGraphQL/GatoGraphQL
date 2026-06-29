<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Users\Constants\InputProperties;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserByOneofInputObjectTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;

class AuthorByOneofInputObjectTypeResolver extends UserByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'AuthorByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the custom post author', 'gatographql');
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->__('Provide author by ID', 'gatographql'),
            InputProperties::USERNAME => $this->__('Provide author by username', 'gatographql'),
            InputProperties::EMAIL => $this->__('Provide author by email', 'gatographql'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return null;
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }
}
