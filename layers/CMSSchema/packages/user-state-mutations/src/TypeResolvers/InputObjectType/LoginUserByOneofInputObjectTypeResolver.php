<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;

class LoginUserByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?LoginCredentialsInputObjectTypeResolver $loginCredentialsInputObjectTypeResolver = null;

    final protected function getLoginCredentialsInputObjectTypeResolver(): LoginCredentialsInputObjectTypeResolver
    {
        if ($this->loginCredentialsInputObjectTypeResolver === null) {
            /** @var LoginCredentialsInputObjectTypeResolver */
            $loginCredentialsInputObjectTypeResolver = $this->instanceManager->getInstance(LoginCredentialsInputObjectTypeResolver::class);
            $this->loginCredentialsInputObjectTypeResolver = $loginCredentialsInputObjectTypeResolver;
        }
        return $this->loginCredentialsInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'LoginUserByInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
