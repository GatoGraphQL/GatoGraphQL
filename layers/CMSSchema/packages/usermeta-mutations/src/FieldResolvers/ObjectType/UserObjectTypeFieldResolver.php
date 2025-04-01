<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType\AbstractUserObjectTypeFieldResolver;
use PoPCMSSchema\UserMetaMutations\Module as UserMetaMutationsModule;
use PoPCMSSchema\UserMetaMutations\ModuleConfiguration as UserMetaMutationsModuleConfiguration;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserObjectTypeFieldResolver extends AbstractUserObjectTypeFieldResolver
{
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?UserDeleteMetaMutationPayloadObjectTypeResolver $userDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?UserAddMetaMutationPayloadObjectTypeResolver $userCreateMutationPayloadObjectTypeResolver = null;
    private ?UserUpdateMetaMutationPayloadObjectTypeResolver $userUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?UserSetMetaMutationPayloadObjectTypeResolver $userSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }
    final protected function getUserDeleteMetaMutationPayloadObjectTypeResolver(): UserDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->userDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var UserDeleteMetaMutationPayloadObjectTypeResolver */
            $userDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->userDeleteMetaMutationPayloadObjectTypeResolver = $userDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->userDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getUserAddMetaMutationPayloadObjectTypeResolver(): UserAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->userCreateMutationPayloadObjectTypeResolver === null) {
            /** @var UserAddMetaMutationPayloadObjectTypeResolver */
            $userCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserAddMetaMutationPayloadObjectTypeResolver::class);
            $this->userCreateMutationPayloadObjectTypeResolver = $userCreateMutationPayloadObjectTypeResolver;
        }
        return $this->userCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getUserUpdateMetaMutationPayloadObjectTypeResolver(): UserUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->userUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var UserUpdateMetaMutationPayloadObjectTypeResolver */
            $userUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->userUpdateMetaMutationPayloadObjectTypeResolver = $userUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->userUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getUserSetMetaMutationPayloadObjectTypeResolver(): UserSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->userSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var UserSetMetaMutationPayloadObjectTypeResolver */
            $userSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserSetMetaMutationPayloadObjectTypeResolver::class);
            $this->userSetMetaMutationPayloadObjectTypeResolver = $userSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->userSetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var UserMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(UserMetaMutationsModule::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        if (!$usePayloadableUserMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getUserObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getUserAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getUserDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getUserSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getUserUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
