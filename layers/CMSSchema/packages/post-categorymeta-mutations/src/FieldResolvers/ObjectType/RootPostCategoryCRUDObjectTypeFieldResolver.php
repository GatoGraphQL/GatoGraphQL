<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType\AbstractRootCategoryCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMetaMutations\Module;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\RootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\RootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\RootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootPostCategoryCRUDObjectTypeFieldResolver extends AbstractRootCategoryCRUDObjectTypeFieldResolver
{
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?RootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver $rootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver $rootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver $rootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver $rootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final protected function getRootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver(): RootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver = $rootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver(): RootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver = $rootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver(): RootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver = $rootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver(): RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver = $rootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if ($usePayloadableCategoryMetaMutations) {
            return match ($fieldName) {
                'addCategoryMeta',
                'addCategoryMetas',
                'addCategoryMetaMutationPayloadObjects'
                    => $this->getRootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver(),
                'updateCategoryMeta',
                'updateCategoryMetas',
                'updateCategoryMetaMutationPayloadObjects'
                    => $this->getRootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver(),
                'deleteCategoryMeta',
                'deleteCategoryMetas',
                'deleteCategoryMetaMutationPayloadObjects'
                    => $this->getRootDeletePostCategoryTermMetaMutationPayloadObjectTypeResolver(),
                'setCategoryMeta',
                'setCategoryMetas',
                'setCategoryMetaMutationPayloadObjects'
                    => $this->getRootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addCategoryMeta',
            'addCategoryMetas',
            'updateCategoryMeta',
            'updateCategoryMetas',
            'deleteCategoryMeta',
            'deleteCategoryMetas',
            'setCategoryMeta',
            'setCategoryMetas'
                => $this->getPostCategoryObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
