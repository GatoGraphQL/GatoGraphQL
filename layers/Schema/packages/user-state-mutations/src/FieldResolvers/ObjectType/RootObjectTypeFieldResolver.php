<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LoginTaggedMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginCredentialsTaggedInputObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?LoginTaggedMutationResolver $loginTaggedMutationResolver = null;
    private ?LogoutMutationResolver $logoutMutationResolver = null;
    private ?LoginCredentialsTaggedInputObjectTypeResolver $loginCredentialsTaggedInputObjectTypeResolver = null;

    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    final public function setLoginTaggedMutationResolver(LoginTaggedMutationResolver $loginTaggedMutationResolver): void
    {
        $this->loginTaggedMutationResolver = $loginTaggedMutationResolver;
    }
    final protected function getLoginTaggedMutationResolver(): LoginTaggedMutationResolver
    {
        return $this->loginTaggedMutationResolver ??= $this->instanceManager->getInstance(LoginTaggedMutationResolver::class);
    }
    final public function setLogoutMutationResolver(LogoutMutationResolver $logoutMutationResolver): void
    {
        $this->logoutMutationResolver = $logoutMutationResolver;
    }
    final protected function getLogoutMutationResolver(): LogoutMutationResolver
    {
        return $this->logoutMutationResolver ??= $this->instanceManager->getInstance(LogoutMutationResolver::class);
    }
    final public function setLoginCredentialsTaggedInputObjectTypeResolver(LoginCredentialsTaggedInputObjectTypeResolver $loginCredentialsTaggedInputObjectTypeResolver): void
    {
        $this->loginCredentialsTaggedInputObjectTypeResolver = $loginCredentialsTaggedInputObjectTypeResolver;
    }
    final protected function getLoginCredentialsTaggedInputObjectTypeResolver(): LoginCredentialsTaggedInputObjectTypeResolver
    {
        return $this->loginCredentialsTaggedInputObjectTypeResolver ??= $this->instanceManager->getInstance(LoginCredentialsTaggedInputObjectTypeResolver::class);
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
            'loginUser' => $this->getTranslationAPI()->__('Log the user in', 'user-state-mutations'),
            'logoutUser' => $this->getTranslationAPI()->__('Log the user out', 'user-state-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'loginUser' => [
                MutationInputProperties::CREDENTIALS => $this->getLoginCredentialsTaggedInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['loginUser' => MutationInputProperties::CREDENTIALS] => $this->getTranslationAPI()->__('Choose which credentials to use to log-in, and provide them', 'user-state-mutations'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['loginUser' => MutationInputProperties::CREDENTIALS] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'loginUser' => $this->getLoginTaggedMutationResolver(),
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
