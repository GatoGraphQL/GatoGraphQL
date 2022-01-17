<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserOneofMutationResolver;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LogoutUserMutationResolver;
use PoPCMSSchema\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginUserByOneofInputObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?LoginUserOneofMutationResolver $loginUserOneofMutationResolver = null;
    private ?LogoutUserMutationResolver $logoutUserMutationResolver = null;
    private ?LoginUserByOneofInputObjectTypeResolver $loginUserByOneofInputObjectTypeResolver = null;

    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    final public function setLoginUserOneofMutationResolver(LoginUserOneofMutationResolver $loginUserOneofMutationResolver): void
    {
        $this->loginUserOneofMutationResolver = $loginUserOneofMutationResolver;
    }
    final protected function getLoginUserOneofMutationResolver(): LoginUserOneofMutationResolver
    {
        return $this->loginUserOneofMutationResolver ??= $this->instanceManager->getInstance(LoginUserOneofMutationResolver::class);
    }
    final public function setLogoutUserMutationResolver(LogoutUserMutationResolver $logoutUserMutationResolver): void
    {
        $this->logoutUserMutationResolver = $logoutUserMutationResolver;
    }
    final protected function getLogoutUserMutationResolver(): LogoutUserMutationResolver
    {
        return $this->logoutUserMutationResolver ??= $this->instanceManager->getInstance(LogoutUserMutationResolver::class);
    }
    final public function setLoginUserByOneofInputObjectTypeResolver(LoginUserByOneofInputObjectTypeResolver $loginUserByOneofInputObjectTypeResolver): void
    {
        $this->loginUserByOneofInputObjectTypeResolver = $loginUserByOneofInputObjectTypeResolver;
    }
    final protected function getLoginUserByOneofInputObjectTypeResolver(): LoginUserByOneofInputObjectTypeResolver
    {
        return $this->loginUserByOneofInputObjectTypeResolver ??= $this->instanceManager->getInstance(LoginUserByOneofInputObjectTypeResolver::class);
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
            'loginUser' => $this->__('Log the user in', 'user-state-mutations'),
            'logoutUser' => $this->__('Log the user out', 'user-state-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'loginUser' => [
                MutationInputProperties::BY => $this->getLoginUserByOneofInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['loginUser' => MutationInputProperties::BY] => $this->__('Choose which credentials to use to log-in, and provide them', 'user-state-mutations'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['loginUser' => MutationInputProperties::BY] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'loginUser' => $this->getLoginUserOneofMutationResolver(),
            'logoutUser' => $this->getLogoutUserMutationResolver(),
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
