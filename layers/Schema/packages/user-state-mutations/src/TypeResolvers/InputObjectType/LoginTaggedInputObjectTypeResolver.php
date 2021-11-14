<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractTaggedInputObjectTypeResolver;
use PoPSchema\UserStateMutations\TypeResolvers\InputObjectType\WebsiteCredentialsInputObjectTypeResolver;

class LoginTaggedInputObjectTypeResolver extends AbstractTaggedInputObjectTypeResolver
{
    private ?WebsiteCredentialsInputObjectTypeResolver $websiteCredentialsInputObjectTypeResolver = null;

    final public function setWebsiteCredentialsInputObjectTypeResolver(WebsiteCredentialsInputObjectTypeResolver $websiteCredentialsInputObjectTypeResolver): void
    {
        $this->websiteCredentialsInputObjectTypeResolver = $websiteCredentialsInputObjectTypeResolver;
    }
    final protected function getWebsiteCredentialsInputObjectTypeResolver(): WebsiteCredentialsInputObjectTypeResolver
    {
        return $this->websiteCredentialsInputObjectTypeResolver ??= $this->instanceManager->getInstance(WebsiteCredentialsInputObjectTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'LoginInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'website' => $this->getWebsiteCredentialsInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'website' => $this->getTranslationAPI()->__('Login using the website credentials', 'user-state-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
