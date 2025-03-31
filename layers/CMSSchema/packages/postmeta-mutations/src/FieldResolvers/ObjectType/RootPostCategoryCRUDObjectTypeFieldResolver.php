<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType\AbstractRootCategoryCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMetaMutations\Module;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootAddPostTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootDeletePostTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootSetPostTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootUpdatePostTermMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootPostCRUDObjectTypeFieldResolver extends AbstractRootCategoryCRUDObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?RootDeletePostTermMetaMutationPayloadObjectTypeResolver $rootDeletePostTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetPostTermMetaMutationPayloadObjectTypeResolver $rootSetPostTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdatePostTermMetaMutationPayloadObjectTypeResolver $rootUpdatePostTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddPostTermMetaMutationPayloadObjectTypeResolver $rootAddPostTermMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final protected function getRootDeletePostTermMetaMutationPayloadObjectTypeResolver(): RootDeletePostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeletePostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeletePostTermMetaMutationPayloadObjectTypeResolver */
            $rootDeletePostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeletePostTermMetaMutationPayloadObjectTypeResolver = $rootDeletePostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeletePostTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetPostTermMetaMutationPayloadObjectTypeResolver(): RootSetPostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetPostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetPostTermMetaMutationPayloadObjectTypeResolver */
            $rootSetPostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetPostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetPostTermMetaMutationPayloadObjectTypeResolver = $rootSetPostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetPostTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePostTermMetaMutationPayloadObjectTypeResolver(): RootUpdatePostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePostTermMetaMutationPayloadObjectTypeResolver */
            $rootUpdatePostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePostTermMetaMutationPayloadObjectTypeResolver = $rootUpdatePostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePostTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddPostTermMetaMutationPayloadObjectTypeResolver(): RootAddPostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddPostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddPostTermMetaMutationPayloadObjectTypeResolver */
            $rootAddPostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddPostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddPostTermMetaMutationPayloadObjectTypeResolver = $rootAddPostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddPostTermMetaMutationPayloadObjectTypeResolver;
    }

    protected function getCategoryEntityName(): string
    {
        return 'Post';
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
                    => $this->getRootAddPostTermMetaMutationPayloadObjectTypeResolver(),
                'update' . $categoryEntityName . 'Meta',
                'update' . $categoryEntityName . 'Metas',
                'update' . $categoryEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdatePostTermMetaMutationPayloadObjectTypeResolver(),
                'delete' . $categoryEntityName . 'Meta',
                'delete' . $categoryEntityName . 'Metas',
                'delete' . $categoryEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeletePostTermMetaMutationPayloadObjectTypeResolver(),
                'set' . $categoryEntityName . 'Meta',
                'set' . $categoryEntityName . 'Metas',
                'set' . $categoryEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetPostTermMetaMutationPayloadObjectTypeResolver(),
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
                => $this->getPostObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
