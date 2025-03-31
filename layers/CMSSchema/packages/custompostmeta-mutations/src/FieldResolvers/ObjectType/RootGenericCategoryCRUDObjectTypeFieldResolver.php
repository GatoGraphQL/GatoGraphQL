<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType\AbstractRootCategoryCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMetaMutations\Module;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootGenericCategoryCRUDObjectTypeFieldResolver extends AbstractRootCategoryCRUDObjectTypeFieldResolver
{
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootSetGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootUpdateGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver $rootAddGenericCustomPostMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
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

    protected function getCategoryEntityName(): string
    {
        return 'Category';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $categoryEntityName = $this->getCategoryEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if ($usePayloadableCategoryMetaMutations) {
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
                => $this->getGenericCategoryObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
