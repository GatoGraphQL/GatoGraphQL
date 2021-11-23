<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;

class LoginUserByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?WebsiteLoginCredentialsInputObjectTypeResolver $websiteLoginCredentialsInputObjectTypeResolver = null;

    final public function setWebsiteLoginCredentialsInputObjectTypeResolver(WebsiteLoginCredentialsInputObjectTypeResolver $websiteLoginCredentialsInputObjectTypeResolver): void
    {
        $this->websiteLoginCredentialsInputObjectTypeResolver = $websiteLoginCredentialsInputObjectTypeResolver;
    }
    final protected function getWebsiteLoginCredentialsInputObjectTypeResolver(): WebsiteLoginCredentialsInputObjectTypeResolver
    {
        return $this->websiteLoginCredentialsInputObjectTypeResolver ??= $this->instanceManager->getInstance(WebsiteLoginCredentialsInputObjectTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'LoginCredentialsInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'credentials' => $this->getWebsiteLoginCredentialsInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'credentials' => $this->getTranslationAPI()->__('Login using the website credentials', 'user-state-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
