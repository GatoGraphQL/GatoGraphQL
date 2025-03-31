<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType\AbstractRootCustomPostCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootGenericCustomPostCRUDObjectTypeFieldResolver extends AbstractRootCustomPostCRUDObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final protected function getRootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver(): RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver */
            $rootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver = $rootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver(): RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver */
            $rootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver = $rootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver(): RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver */
            $rootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver = $rootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver(): RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver */
            $rootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver = $rootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver;
    }

    protected function getCustomPostEntityName(): string
    {
        return 'CustomPost';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $customPostEntityName = $this->getCustomPostEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if ($usePayloadableCustomPostMetaMutations) {
            return match ($fieldName) {
                'add' . $customPostEntityName . 'Meta',
                'add' . $customPostEntityName . 'Metas',
                'add' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                'update' . $customPostEntityName . 'Meta',
                'update' . $customPostEntityName . 'Metas',
                'update' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                'delete' . $customPostEntityName . 'Meta',
                'delete' . $customPostEntityName . 'Metas',
                'delete' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                'set' . $customPostEntityName . 'Meta',
                'set' . $customPostEntityName . 'Metas',
                'set' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'add' . $customPostEntityName . 'Meta',
            'add' . $customPostEntityName . 'Metas',
            'update' . $customPostEntityName . 'Meta',
            'update' . $customPostEntityName . 'Metas',
            'delete' . $customPostEntityName . 'Meta',
            'delete' . $customPostEntityName . 'Metas',
            'set' . $customPostEntityName . 'Meta',
            'set' . $customPostEntityName . 'Metas'
                => $this->getGenericCustomPostObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
