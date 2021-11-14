<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;

class WebsiteLoginCredentialsInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'WebsiteLoginCredentials';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => $this->getStringScalarTypeResolver(),
            MutationInputProperties::PASSWORD => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::USERNAME_OR_EMAIL => $this->getTranslationAPI()->__('The username or email', 'user-state-mutations'),
            MutationInputProperties::PASSWORD => $this->getTranslationAPI()->__('The password', 'user-state-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::USERNAME_OR_EMAIL,
            MutationInputProperties::PASSWORD
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
