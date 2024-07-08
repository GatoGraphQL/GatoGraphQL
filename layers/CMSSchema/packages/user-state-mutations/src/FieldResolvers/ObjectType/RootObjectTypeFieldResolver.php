<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\Module;
use PoPCMSSchema\UserStateMutations\ModuleConfiguration;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserOneofMutationResolver;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LogoutUserMutationResolver;
use PoPCMSSchema\UserStateMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLoginUserOneofMutationResolver;
use PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLogoutUserMutationResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginUserByOneofInputObjectTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLoginUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLogoutUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?LoginUserOneofMutationResolver $loginUserOneofMutationResolver = null;
    private ?LogoutUserMutationResolver $logoutUserMutationResolver = null;
    private ?LoginUserByOneofInputObjectTypeResolver $loginUserByOneofInputObjectTypeResolver = null;
    private ?PayloadableLoginUserOneofMutationResolver $payloadableLoginUserOneofMutationResolver = null;
    private ?PayloadableLogoutUserMutationResolver $payloadableLogoutUserMutationResolver = null;
    private ?RootLoginUserMutationPayloadObjectTypeResolver $rootLoginUserMutationPayloadObjectTypeResolver = null;
    private ?RootLogoutUserMutationPayloadObjectTypeResolver $rootLogoutUserMutationPayloadObjectTypeResolver = null;

    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }
    final public function setLoginUserOneofMutationResolver(LoginUserOneofMutationResolver $loginUserOneofMutationResolver): void
    {
        $this->loginUserOneofMutationResolver = $loginUserOneofMutationResolver;
    }
    final protected function getLoginUserOneofMutationResolver(): LoginUserOneofMutationResolver
    {
        if ($this->loginUserOneofMutationResolver === null) {
            /** @var LoginUserOneofMutationResolver */
            $loginUserOneofMutationResolver = $this->instanceManager->getInstance(LoginUserOneofMutationResolver::class);
            $this->loginUserOneofMutationResolver = $loginUserOneofMutationResolver;
        }
        return $this->loginUserOneofMutationResolver;
    }
    final public function setLogoutUserMutationResolver(LogoutUserMutationResolver $logoutUserMutationResolver): void
    {
        $this->logoutUserMutationResolver = $logoutUserMutationResolver;
    }
    final protected function getLogoutUserMutationResolver(): LogoutUserMutationResolver
    {
        if ($this->logoutUserMutationResolver === null) {
            /** @var LogoutUserMutationResolver */
            $logoutUserMutationResolver = $this->instanceManager->getInstance(LogoutUserMutationResolver::class);
            $this->logoutUserMutationResolver = $logoutUserMutationResolver;
        }
        return $this->logoutUserMutationResolver;
    }
    final public function setLoginUserByOneofInputObjectTypeResolver(LoginUserByOneofInputObjectTypeResolver $loginUserByOneofInputObjectTypeResolver): void
    {
        $this->loginUserByOneofInputObjectTypeResolver = $loginUserByOneofInputObjectTypeResolver;
    }
    final protected function getLoginUserByOneofInputObjectTypeResolver(): LoginUserByOneofInputObjectTypeResolver
    {
        if ($this->loginUserByOneofInputObjectTypeResolver === null) {
            /** @var LoginUserByOneofInputObjectTypeResolver */
            $loginUserByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(LoginUserByOneofInputObjectTypeResolver::class);
            $this->loginUserByOneofInputObjectTypeResolver = $loginUserByOneofInputObjectTypeResolver;
        }
        return $this->loginUserByOneofInputObjectTypeResolver;
    }
    final public function setPayloadableLoginUserOneofMutationResolver(PayloadableLoginUserOneofMutationResolver $payloadableLoginUserOneofMutationResolver): void
    {
        $this->payloadableLoginUserOneofMutationResolver = $payloadableLoginUserOneofMutationResolver;
    }
    final protected function getPayloadableLoginUserOneofMutationResolver(): PayloadableLoginUserOneofMutationResolver
    {
        if ($this->payloadableLoginUserOneofMutationResolver === null) {
            /** @var PayloadableLoginUserOneofMutationResolver */
            $payloadableLoginUserOneofMutationResolver = $this->instanceManager->getInstance(PayloadableLoginUserOneofMutationResolver::class);
            $this->payloadableLoginUserOneofMutationResolver = $payloadableLoginUserOneofMutationResolver;
        }
        return $this->payloadableLoginUserOneofMutationResolver;
    }
    final public function setPayloadableLogoutUserMutationResolver(PayloadableLogoutUserMutationResolver $payloadableLogoutUserMutationResolver): void
    {
        $this->payloadableLogoutUserMutationResolver = $payloadableLogoutUserMutationResolver;
    }
    final protected function getPayloadableLogoutUserMutationResolver(): PayloadableLogoutUserMutationResolver
    {
        if ($this->payloadableLogoutUserMutationResolver === null) {
            /** @var PayloadableLogoutUserMutationResolver */
            $payloadableLogoutUserMutationResolver = $this->instanceManager->getInstance(PayloadableLogoutUserMutationResolver::class);
            $this->payloadableLogoutUserMutationResolver = $payloadableLogoutUserMutationResolver;
        }
        return $this->payloadableLogoutUserMutationResolver;
    }
    final public function setRootLoginUserMutationPayloadObjectTypeResolver(RootLoginUserMutationPayloadObjectTypeResolver $rootLoginUserMutationPayloadObjectTypeResolver): void
    {
        $this->rootLoginUserMutationPayloadObjectTypeResolver = $rootLoginUserMutationPayloadObjectTypeResolver;
    }
    final protected function getRootLoginUserMutationPayloadObjectTypeResolver(): RootLoginUserMutationPayloadObjectTypeResolver
    {
        if ($this->rootLoginUserMutationPayloadObjectTypeResolver === null) {
            /** @var RootLoginUserMutationPayloadObjectTypeResolver */
            $rootLoginUserMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootLoginUserMutationPayloadObjectTypeResolver::class);
            $this->rootLoginUserMutationPayloadObjectTypeResolver = $rootLoginUserMutationPayloadObjectTypeResolver;
        }
        return $this->rootLoginUserMutationPayloadObjectTypeResolver;
    }
    final public function setRootLogoutUserMutationPayloadObjectTypeResolver(RootLogoutUserMutationPayloadObjectTypeResolver $rootLogoutUserMutationPayloadObjectTypeResolver): void
    {
        $this->rootLogoutUserMutationPayloadObjectTypeResolver = $rootLogoutUserMutationPayloadObjectTypeResolver;
    }
    final protected function getRootLogoutUserMutationPayloadObjectTypeResolver(): RootLogoutUserMutationPayloadObjectTypeResolver
    {
        if ($this->rootLogoutUserMutationPayloadObjectTypeResolver === null) {
            /** @var RootLogoutUserMutationPayloadObjectTypeResolver */
            $rootLogoutUserMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootLogoutUserMutationPayloadObjectTypeResolver::class);
            $this->rootLogoutUserMutationPayloadObjectTypeResolver = $rootLogoutUserMutationPayloadObjectTypeResolver;
        }
        return $this->rootLogoutUserMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserStateMutations = $moduleConfiguration->usePayloadableUserStateMutations();
        if (!$usePayloadableUserStateMutations) {
            return match ($fieldName) {
                'loginUser',
                'logoutUser' =>
                    SchemaTypeModifiers::NONE,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'loginUser',
            'logoutUser' =>
                SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserStateMutations = $moduleConfiguration->usePayloadableUserStateMutations();
        return match ($fieldName) {
            'loginUser' => $usePayloadableUserStateMutations
                ? $this->getPayloadableLoginUserOneofMutationResolver()
                : $this->getLoginUserOneofMutationResolver(),
            'logoutUser' => $usePayloadableUserStateMutations
                ? $this->getPayloadableLogoutUserMutationResolver()
                : $this->getLogoutUserMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserStateMutations = $moduleConfiguration->usePayloadableUserStateMutations();
        if ($usePayloadableUserStateMutations) {
            return match ($fieldName) {
                'loginUser' => $this->getRootLoginUserMutationPayloadObjectTypeResolver(),
                'logoutUser' => $this->getRootLogoutUserMutationPayloadObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'loginUser',
            'logoutUser'
                => $this->getUserObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
