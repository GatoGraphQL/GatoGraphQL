<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LoginMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?LoginMutationResolver $loginMutationResolver = null;
    private ?LogoutMutationResolver $logoutMutationResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    public function setLoginMutationResolver(LoginMutationResolver $loginMutationResolver): void
    {
        $this->loginMutationResolver = $loginMutationResolver;
    }
    protected function getLoginMutationResolver(): LoginMutationResolver
    {
        return $this->loginMutationResolver ??= $this->instanceManager->getInstance(LoginMutationResolver::class);
    }
    public function setLogoutMutationResolver(LogoutMutationResolver $logoutMutationResolver): void
    {
        $this->logoutMutationResolver = $logoutMutationResolver;
    }
    protected function getLogoutMutationResolver(): LogoutMutationResolver
    {
        return $this->logoutMutationResolver ??= $this->instanceManager->getInstance(LogoutMutationResolver::class);
    }
    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'loginUser',
            'logoutUser',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'loginUser' => $this->translationAPI->__('Log the user in', 'user-state-mutations'),
            'logoutUser' => $this->translationAPI->__('Log the user out', 'user-state-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'loginUser' => [
                MutationInputProperties::USERNAME_OR_EMAIL => $this->getStringScalarTypeResolver(),
                MutationInputProperties::PASSWORD => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['loginUser' => MutationInputProperties::USERNAME_OR_EMAIL] => $this->translationAPI->__('The username or email', 'user-state-mutations'),
            ['loginUser' => MutationInputProperties::PASSWORD] => $this->translationAPI->__('The password', 'user-state-mutations'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['loginUser' => MutationInputProperties::USERNAME_OR_EMAIL],
            ['loginUser' => MutationInputProperties::PASSWORD]
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'loginUser' => $this->getLoginMutationResolver(),
            'logoutUser' => $this->getLogoutMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'loginUser',
            'logoutUser'
                => $this->getUserObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
