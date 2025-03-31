<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMetaMutations\Module as CategoryMetaMutationsModule;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration as CategoryMetaMutationsModuleConfiguration;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\PostCategoryAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\PostCategoryDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\PostCategorySetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\PostCategoryUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostCategoryDeleteMetaMutationPayloadObjectTypeResolver $postCategoryDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?PostCategoryAddMetaMutationPayloadObjectTypeResolver $postCategoryCreateMutationPayloadObjectTypeResolver = null;
    private ?PostCategoryUpdateMetaMutationPayloadObjectTypeResolver $postCategoryUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?PostCategorySetMetaMutationPayloadObjectTypeResolver $postCategorySetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryDeleteMetaMutationPayloadObjectTypeResolver(): PostCategoryDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostCategoryDeleteMetaMutationPayloadObjectTypeResolver */
            $postCategoryDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->postCategoryDeleteMetaMutationPayloadObjectTypeResolver = $postCategoryDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostCategoryAddMetaMutationPayloadObjectTypeResolver(): PostCategoryAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryCreateMutationPayloadObjectTypeResolver === null) {
            /** @var PostCategoryAddMetaMutationPayloadObjectTypeResolver */
            $postCategoryCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryAddMetaMutationPayloadObjectTypeResolver::class);
            $this->postCategoryCreateMutationPayloadObjectTypeResolver = $postCategoryCreateMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostCategoryUpdateMetaMutationPayloadObjectTypeResolver(): PostCategoryUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostCategoryUpdateMetaMutationPayloadObjectTypeResolver */
            $postCategoryUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->postCategoryUpdateMetaMutationPayloadObjectTypeResolver = $postCategoryUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostCategorySetMetaMutationPayloadObjectTypeResolver(): PostCategorySetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postCategorySetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostCategorySetMetaMutationPayloadObjectTypeResolver */
            $postCategorySetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostCategorySetMetaMutationPayloadObjectTypeResolver::class);
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
            PostCategoryObjectTypeResolver::class,
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
                    => $this->getPostCategoryObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getPostCategoryAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getPostCategoryDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getPostCategorySetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getPostCategoryUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
