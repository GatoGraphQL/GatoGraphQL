<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootCreateCustomPostFilterInputObjectTypeResolver;
use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootUpdateCustomPostFilterInputObjectTypeResolver;
use PoPSchema\PostMutations\MutationResolvers\CreatePostMutationResolver;
use PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?CreatePostMutationResolver $createPostMutationResolver = null;
    private ?UpdatePostMutationResolver $updatePostMutationResolver = null;
    private ?RootUpdateCustomPostFilterInputObjectTypeResolver $rootUpdateCustomPostFilterInputObjectTypeResolver = null;
    private ?RootCreateCustomPostFilterInputObjectTypeResolver $rootCreateCustomPostFilterInputObjectTypeResolver = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setCreatePostMutationResolver(CreatePostMutationResolver $createPostMutationResolver): void
    {
        $this->createPostMutationResolver = $createPostMutationResolver;
    }
    final protected function getCreatePostMutationResolver(): CreatePostMutationResolver
    {
        return $this->createPostMutationResolver ??= $this->instanceManager->getInstance(CreatePostMutationResolver::class);
    }
    final public function setUpdatePostMutationResolver(UpdatePostMutationResolver $updatePostMutationResolver): void
    {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }
    final protected function getUpdatePostMutationResolver(): UpdatePostMutationResolver
    {
        return $this->updatePostMutationResolver ??= $this->instanceManager->getInstance(UpdatePostMutationResolver::class);
    }
    final public function setRootUpdateCustomPostFilterInputObjectTypeResolver(RootUpdateCustomPostFilterInputObjectTypeResolver $rootUpdateCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootUpdateCustomPostFilterInputObjectTypeResolver = $rootUpdateCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootUpdateCustomPostFilterInputObjectTypeResolver(): RootUpdateCustomPostFilterInputObjectTypeResolver
    {
        return $this->rootUpdateCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootUpdateCustomPostFilterInputObjectTypeResolver::class);
    }
    final public function setRootCreateCustomPostFilterInputObjectTypeResolver(RootCreateCustomPostFilterInputObjectTypeResolver $rootCreateCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootCreateCustomPostFilterInputObjectTypeResolver = $rootCreateCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootCreateCustomPostFilterInputObjectTypeResolver(): RootCreateCustomPostFilterInputObjectTypeResolver
    {
        return $this->rootCreateCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootCreateCustomPostFilterInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return array_merge(
            [
                'createPost',
            ],
            !EngineComponentConfiguration::disableRedundantRootTypeMutationFields() ? [
                'updatePost',
            ] : []
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createPost' => $this->getTranslationAPI()->__('Create a post', 'post-mutations'),
            'updatePost' => $this->getTranslationAPI()->__('Update a post', 'post-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'createPost' => [
                'input' => $this->getRootCreateCustomPostFilterInputObjectTypeResolver(),
            ],
            'updatePost' => [
                'input' => $this->getRootUpdateCustomPostFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            'input' => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'createPost' => $this->getCreatePostMutationResolver(),
            'updatePost' => $this->getUpdatePostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'createPost',
            'updatePost'
                => $this->getPostObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
