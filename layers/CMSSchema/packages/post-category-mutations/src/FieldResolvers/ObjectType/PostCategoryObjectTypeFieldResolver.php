<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMutations\Module as CategoryMutationsModule;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration as CategoryMutationsModuleConfiguration;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\DeletePostCategoryTermMutationResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\PayloadableDeletePostCategoryTermMutationResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\PayloadableUpdatePostCategoryTermMutationResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\UpdatePostCategoryTermMutationResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\PostCategoryDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\PostCategoryUpdateMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class PostCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostCategoryUpdateMutationPayloadObjectTypeResolver $postCategoryUpdateMutationPayloadObjectTypeResolver = null;
    private ?PostCategoryDeleteMutationPayloadObjectTypeResolver $postCategoryDeleteMutationPayloadObjectTypeResolver = null;
    private ?UpdatePostCategoryTermMutationResolver $updatePostCategoryTermMutationResolver = null;
    private ?DeletePostCategoryTermMutationResolver $deletePostCategoryTermMutationResolver = null;
    private ?PayloadableUpdatePostCategoryTermMutationResolver $payloadableUpdatePostCategoryTermMutationResolver = null;
    private ?PayloadableDeletePostCategoryTermMutationResolver $payloadableDeletePostCategoryTermMutationResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final public function setPostCategoryUpdateMutationPayloadObjectTypeResolver(PostCategoryUpdateMutationPayloadObjectTypeResolver $postCategoryUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->postCategoryUpdateMutationPayloadObjectTypeResolver = $postCategoryUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostCategoryUpdateMutationPayloadObjectTypeResolver(): PostCategoryUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var PostCategoryUpdateMutationPayloadObjectTypeResolver */
            $postCategoryUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryUpdateMutationPayloadObjectTypeResolver::class);
            $this->postCategoryUpdateMutationPayloadObjectTypeResolver = $postCategoryUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryUpdateMutationPayloadObjectTypeResolver;
    }
    final public function setPostCategoryDeleteMutationPayloadObjectTypeResolver(PostCategoryDeleteMutationPayloadObjectTypeResolver $postCategoryDeleteMutationPayloadObjectTypeResolver): void
    {
        $this->postCategoryDeleteMutationPayloadObjectTypeResolver = $postCategoryDeleteMutationPayloadObjectTypeResolver;
    }
    final protected function getPostCategoryDeleteMutationPayloadObjectTypeResolver(): PostCategoryDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->postCategoryDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var PostCategoryDeleteMutationPayloadObjectTypeResolver */
            $postCategoryDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryDeleteMutationPayloadObjectTypeResolver::class);
            $this->postCategoryDeleteMutationPayloadObjectTypeResolver = $postCategoryDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->postCategoryDeleteMutationPayloadObjectTypeResolver;
    }
    final public function setUpdatePostCategoryTermMutationResolver(UpdatePostCategoryTermMutationResolver $updatePostCategoryTermMutationResolver): void
    {
        $this->updatePostCategoryTermMutationResolver = $updatePostCategoryTermMutationResolver;
    }
    final protected function getUpdatePostCategoryTermMutationResolver(): UpdatePostCategoryTermMutationResolver
    {
        if ($this->updatePostCategoryTermMutationResolver === null) {
            /** @var UpdatePostCategoryTermMutationResolver */
            $updatePostCategoryTermMutationResolver = $this->instanceManager->getInstance(UpdatePostCategoryTermMutationResolver::class);
            $this->updatePostCategoryTermMutationResolver = $updatePostCategoryTermMutationResolver;
        }
        return $this->updatePostCategoryTermMutationResolver;
    }
    final public function setDeletePostCategoryTermMutationResolver(DeletePostCategoryTermMutationResolver $deletePostCategoryTermMutationResolver): void
    {
        $this->deletePostCategoryTermMutationResolver = $deletePostCategoryTermMutationResolver;
    }
    final protected function getDeletePostCategoryTermMutationResolver(): DeletePostCategoryTermMutationResolver
    {
        if ($this->deletePostCategoryTermMutationResolver === null) {
            /** @var DeletePostCategoryTermMutationResolver */
            $deletePostCategoryTermMutationResolver = $this->instanceManager->getInstance(DeletePostCategoryTermMutationResolver::class);
            $this->deletePostCategoryTermMutationResolver = $deletePostCategoryTermMutationResolver;
        }
        return $this->deletePostCategoryTermMutationResolver;
    }
    final public function setPayloadableUpdatePostCategoryTermMutationResolver(PayloadableUpdatePostCategoryTermMutationResolver $payloadableUpdatePostCategoryTermMutationResolver): void
    {
        $this->payloadableUpdatePostCategoryTermMutationResolver = $payloadableUpdatePostCategoryTermMutationResolver;
    }
    final protected function getPayloadableUpdatePostCategoryTermMutationResolver(): PayloadableUpdatePostCategoryTermMutationResolver
    {
        if ($this->payloadableUpdatePostCategoryTermMutationResolver === null) {
            /** @var PayloadableUpdatePostCategoryTermMutationResolver */
            $payloadableUpdatePostCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePostCategoryTermMutationResolver::class);
            $this->payloadableUpdatePostCategoryTermMutationResolver = $payloadableUpdatePostCategoryTermMutationResolver;
        }
        return $this->payloadableUpdatePostCategoryTermMutationResolver;
    }
    final public function setPayloadableDeletePostCategoryTermMutationResolver(PayloadableDeletePostCategoryTermMutationResolver $payloadableDeletePostCategoryTermMutationResolver): void
    {
        $this->payloadableDeletePostCategoryTermMutationResolver = $payloadableDeletePostCategoryTermMutationResolver;
    }
    final protected function getPayloadableDeletePostCategoryTermMutationResolver(): PayloadableDeletePostCategoryTermMutationResolver
    {
        if ($this->payloadableDeletePostCategoryTermMutationResolver === null) {
            /** @var PayloadableDeletePostCategoryTermMutationResolver */
            $payloadableDeletePostCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePostCategoryTermMutationResolver::class);
            $this->payloadableDeletePostCategoryTermMutationResolver = $payloadableDeletePostCategoryTermMutationResolver;
        }
        return $this->payloadableDeletePostCategoryTermMutationResolver;
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
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

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the post category', 'category-mutations'),
            'delete' => $this->__('Delete the post category', 'category-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'update' => [
                'input' => $this->getCategoryTermUpdateInputObjectTypeResolver(),
            ],
            'delete' => [],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CategoryMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoryMutationsModule::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdatePostCategoryTermMutationResolver()
                : $this->getUpdatePostCategoryTermMutationResolver(),
            'delete' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeletePostCategoryTermMutationResolver()
                : $this->getDeletePostCategoryTermMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CategoryMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoryMutationsModule::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        if (!$usePayloadableCategoryMutations) {
            return match ($fieldName) {
                'update' => $this->getPostCategoryObjectTypeResolver(),
                'delete' => $this->getBooleanScalarTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'update' => $this->getPostCategoryUpdateMutationPayloadObjectTypeResolver(),
            'delete' => $this->getPostCategoryDeleteMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
