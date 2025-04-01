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

    protected function getUserEntityName(): string
    {
        return 'User';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $userEntityName = $this->getUserEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserMetaMutations = $moduleConfiguration->usePayloadableUserMetaMutations();
        if ($usePayloadableUserMetaMutations) {
            return match ($fieldName) {
                'add' . $userEntityName . 'Meta',
                'add' . $userEntityName . 'Metas',
                'add' . $userEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootAddUserMetaMutationPayloadObjectTypeResolver(),
                'update' . $userEntityName . 'Meta',
                'update' . $userEntityName . 'Metas',
                'update' . $userEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdateUserMetaMutationPayloadObjectTypeResolver(),
                'delete' . $userEntityName . 'Meta',
                'delete' . $userEntityName . 'Metas',
                'delete' . $userEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeleteUserMetaMutationPayloadObjectTypeResolver(),
                'set' . $userEntityName . 'Meta',
                'set' . $userEntityName . 'Metas',
                'set' . $userEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetUserMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'add' . $userEntityName . 'Meta',
            'add' . $userEntityName . 'Metas',
            'update' . $userEntityName . 'Meta',
            'update' . $userEntityName . 'Metas',
            'delete' . $userEntityName . 'Meta',
            'delete' . $userEntityName . 'Metas',
            'set' . $userEntityName . 'Meta',
            'set' . $userEntityName . 'Metas'
                => $this->getUserObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
