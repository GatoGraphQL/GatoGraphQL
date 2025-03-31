<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
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
        $categoryEntityName = $this->getCustomPostEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if ($usePayloadableCustomPostMetaMutations) {
            return match ($fieldName) {
                'add' . $categoryEntityName . 'Meta',
                'add' . $categoryEntityName . 'Metas',
                'add' . $categoryEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                'update' . $categoryEntityName . 'Meta',
                'update' . $categoryEntityName . 'Metas',
                'update' . $categoryEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                'delete' . $categoryEntityName . 'Meta',
                'delete' . $categoryEntityName . 'Metas',
                'delete' . $categoryEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                'set' . $categoryEntityName . 'Meta',
                'set' . $categoryEntityName . 'Metas',
                'set' . $categoryEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta',
            'add' . $categoryEntityName . 'Metas',
            'update' . $categoryEntityName . 'Meta',
            'update' . $categoryEntityName . 'Metas',
            'delete' . $categoryEntityName . 'Meta',
            'delete' . $categoryEntityName . 'Metas',
            'set' . $categoryEntityName . 'Meta',
            'set' . $categoryEntityName . 'Metas'
                => $this->getGenericCustomPostObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
