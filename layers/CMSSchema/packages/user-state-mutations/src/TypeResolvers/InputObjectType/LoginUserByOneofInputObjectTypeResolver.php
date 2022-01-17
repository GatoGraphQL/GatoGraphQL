<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;

class LoginUserByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?LoginCredentialsInputObjectTypeResolver $loginCredentialsInputObjectTypeResolver = null;

    final public function setLoginCredentialsInputObjectTypeResolver(LoginCredentialsInputObjectTypeResolver $loginCredentialsInputObjectTypeResolver): void
    {
        $this->loginCredentialsInputObjectTypeResolver = $loginCredentialsInputObjectTypeResolver;
    }
    final protected function getLoginCredentialsInputObjectTypeResolver(): LoginCredentialsInputObjectTypeResolver
    {
        return $this->loginCredentialsInputObjectTypeResolver ??= $this->instanceManager->getInstance(LoginCredentialsInputObjectTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'LoginUserByInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'credentials' => $this->getLoginCredentialsInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'credentials' => $this->__('Login using the website credentials', 'user-state-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
