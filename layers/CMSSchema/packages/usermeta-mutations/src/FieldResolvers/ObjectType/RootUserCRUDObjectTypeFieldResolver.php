<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType\AbstractRootUserCRUDObjectTypeFieldResolver;
use PoPCMSSchema\UserMetaMutations\Module;
use PoPCMSSchema\UserMetaMutations\ModuleConfiguration;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootAddUserMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootDeleteUserMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootSetUserMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootUpdateUserMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootUserCRUDObjectTypeFieldResolver extends AbstractRootUserCRUDObjectTypeFieldResolver
{
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?RootDeleteUserMetaMutationPayloadObjectTypeResolver $rootDeleteUserMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetUserMetaMutationPayloadObjectTypeResolver $rootSetUserMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateUserMetaMutationPayloadObjectTypeResolver $rootUpdateUserMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddUserMetaMutationPayloadObjectTypeResolver $rootAddUserMetaMutationPayloadObjectTypeResolver = null;

    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }
    final protected function getRootDeleteUserMetaMutationPayloadObjectTypeResolver(): RootDeleteUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteUserMetaMutationPayloadObjectTypeResolver */
            $rootDeleteUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteUserMetaMutationPayloadObjectTypeResolver = $rootDeleteUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteUserMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetUserMetaMutationPayloadObjectTypeResolver(): RootSetUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetUserMetaMutationPayloadObjectTypeResolver */
            $rootSetUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetUserMetaMutationPayloadObjectTypeResolver = $rootSetUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetUserMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateUserMetaMutationPayloadObjectTypeResolver(): RootUpdateUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateUserMetaMutationPayloadObjectTypeResolver */
            $rootUpdateUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateUserMetaMutationPayloadObjectTypeResolver = $rootUpdateUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateUserMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddUserMetaMutationPayloadObjectTypeResolver(): RootAddUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddUserMetaMutationPayloadObjectTypeResolver */
            $rootAddUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddUserMetaMutationPayloadObjectTypeResolver = $rootAddUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddUserMetaMutationPayloadObjectTypeResolver;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        if ($usePayloadableUserMetaMutations) {
            return match ($fieldName) {
                'addUserMeta',
                'addUserMetas',
                'addUserMetaMutationPayloadObjects'
                    => $this->getRootAddUserMetaMutationPayloadObjectTypeResolver(),
                'updateUserMeta',
                'updateUserMetas',
                'updateUserMetaMutationPayloadObjects'
                    => $this->getRootUpdateUserMetaMutationPayloadObjectTypeResolver(),
                'deleteUserMeta',
                'deleteUserMetas',
                'deleteUserMetaMutationPayloadObjects'
                    => $this->getRootDeleteUserMetaMutationPayloadObjectTypeResolver(),
                'setUserMeta',
                'setUserMetas',
                'setUserMetaMutationPayloadObjects'
                    => $this->getRootSetUserMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addUserMeta',
            'addUserMetas',
            'updateUserMeta',
            'updateUserMetas',
            'deleteUserMeta',
            'deleteUserMetas',
            'setUserMeta',
            'setUserMetas'
                => $this->getUserObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
