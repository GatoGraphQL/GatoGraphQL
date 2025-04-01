<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Users\TypeResolvers\ObjectType\GenericUserObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType\AbstractRootUserCRUDObjectTypeFieldResolver;
use PoPCMSSchema\UserMetaMutations\Module;
use PoPCMSSchema\UserMetaMutations\ModuleConfiguration;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootAddGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootSetGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootGenericUserCRUDObjectTypeFieldResolver extends AbstractRootUserCRUDObjectTypeFieldResolver
{
    private ?GenericUserObjectTypeResolver $genericUserObjectTypeResolver = null;
    private ?RootDeleteGenericUserMetaMutationPayloadObjectTypeResolver $rootDeleteGenericUserMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetGenericUserMetaMutationPayloadObjectTypeResolver $rootSetGenericUserMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver $rootUpdateGenericUserMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddGenericUserMetaMutationPayloadObjectTypeResolver $rootAddGenericUserMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericUserObjectTypeResolver(): GenericUserObjectTypeResolver
    {
        if ($this->genericUserObjectTypeResolver === null) {
            /** @var GenericUserObjectTypeResolver */
            $genericUserObjectTypeResolver = $this->instanceManager->getInstance(GenericUserObjectTypeResolver::class);
            $this->genericUserObjectTypeResolver = $genericUserObjectTypeResolver;
        }
        return $this->genericUserObjectTypeResolver;
    }
    final protected function getRootDeleteGenericUserMetaMutationPayloadObjectTypeResolver(): RootDeleteGenericUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteGenericUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteGenericUserMetaMutationPayloadObjectTypeResolver */
            $rootDeleteGenericUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteGenericUserMetaMutationPayloadObjectTypeResolver = $rootDeleteGenericUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteGenericUserMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetGenericUserMetaMutationPayloadObjectTypeResolver(): RootSetGenericUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetGenericUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetGenericUserMetaMutationPayloadObjectTypeResolver */
            $rootSetGenericUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetGenericUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetGenericUserMetaMutationPayloadObjectTypeResolver = $rootSetGenericUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetGenericUserMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericUserMetaMutationPayloadObjectTypeResolver(): RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver */
            $rootUpdateGenericUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericUserMetaMutationPayloadObjectTypeResolver = $rootUpdateGenericUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericUserMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddGenericUserMetaMutationPayloadObjectTypeResolver(): RootAddGenericUserMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddGenericUserMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddGenericUserMetaMutationPayloadObjectTypeResolver */
            $rootAddGenericUserMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddGenericUserMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddGenericUserMetaMutationPayloadObjectTypeResolver = $rootAddGenericUserMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddGenericUserMetaMutationPayloadObjectTypeResolver;
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
                    => $this->getRootAddGenericUserMetaMutationPayloadObjectTypeResolver(),
                'update' . $userEntityName . 'Meta',
                'update' . $userEntityName . 'Metas',
                'update' . $userEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdateGenericUserMetaMutationPayloadObjectTypeResolver(),
                'delete' . $userEntityName . 'Meta',
                'delete' . $userEntityName . 'Metas',
                'delete' . $userEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeleteGenericUserMetaMutationPayloadObjectTypeResolver(),
                'set' . $userEntityName . 'Meta',
                'set' . $userEntityName . 'Metas',
                'set' . $userEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetGenericUserMetaMutationPayloadObjectTypeResolver(),
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
                => $this->getGenericUserObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
