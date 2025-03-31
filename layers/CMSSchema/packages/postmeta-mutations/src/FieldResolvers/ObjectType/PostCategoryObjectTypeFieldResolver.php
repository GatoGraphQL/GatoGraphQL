<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMetaMutations\Module as CategoryMetaMutationsModule;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration as CategoryMetaMutationsModuleConfiguration;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostDeleteMetaMutationPayloadObjectTypeResolver $postCategoryDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?PostAddMetaMutationPayloadObjectTypeResolver $postCategoryCreateMutationPayloadObjectTypeResolver = null;
    private ?PostUpdateMetaMutationPayloadObjectTypeResolver $postCategoryUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?PostSetMetaMutationPayloadObjectTypeResolver $postCategorySetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final protected function getPostDeleteMetaMutationPayloadObjectTypeResolver(): PostDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostDeleteMetaMutationPayloadObjectTypeResolver */
            $postCategoryDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->postCategoryDeleteMetaMutationPayloadObjectTypeResolver = $postCategoryDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostAddMetaMutationPayloadObjectTypeResolver(): PostAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryCreateMutationPayloadObjectTypeResolver === null) {
            /** @var PostAddMetaMutationPayloadObjectTypeResolver */
            $postCategoryCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostAddMetaMutationPayloadObjectTypeResolver::class);
            $this->postCategoryCreateMutationPayloadObjectTypeResolver = $postCategoryCreateMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostUpdateMetaMutationPayloadObjectTypeResolver(): PostUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostUpdateMetaMutationPayloadObjectTypeResolver */
            $postCategoryUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->postCategoryUpdateMetaMutationPayloadObjectTypeResolver = $postCategoryUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostSetMetaMutationPayloadObjectTypeResolver(): PostSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategorySetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostSetMetaMutationPayloadObjectTypeResolver */
            $postCategorySetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostSetMetaMutationPayloadObjectTypeResolver::class);
            $this->postCategorySetMetaMutationPayloadObjectTypeResolver = $postCategorySetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCategorySetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CategoryMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoryMetaMutationsModule::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if (!$usePayloadableCategoryMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getPostObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getPostAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getPostDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getPostSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getPostUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
