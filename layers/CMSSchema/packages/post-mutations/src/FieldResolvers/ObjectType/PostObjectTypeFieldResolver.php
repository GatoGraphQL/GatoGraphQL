<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\PostUpdateFilterInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?UpdatePostMutationResolver $updatePostMutationResolver = null;
    private ?PostUpdateFilterInputObjectTypeResolver $postUpdateFilterInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setUpdatePostMutationResolver(UpdatePostMutationResolver $updatePostMutationResolver): void
    {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }
    final protected function getUpdatePostMutationResolver(): UpdatePostMutationResolver
    {
        return $this->updatePostMutationResolver ??= $this->instanceManager->getInstance(UpdatePostMutationResolver::class);
    }
    final public function setPostUpdateFilterInputObjectTypeResolver(PostUpdateFilterInputObjectTypeResolver $postUpdateFilterInputObjectTypeResolver): void
    {
        $this->postUpdateFilterInputObjectTypeResolver = $postUpdateFilterInputObjectTypeResolver;
    }
    final protected function getPostUpdateFilterInputObjectTypeResolver(): PostUpdateFilterInputObjectTypeResolver
    {
        return $this->postUpdateFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(PostUpdateFilterInputObjectTypeResolver::class);
    }
    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        return $this->userLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
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

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the post', 'post-mutations'),
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
                'input' => $this->getPostUpdateFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'update' => $this->getUpdatePostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'update' => $this->getPostObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );
        $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
        return $validationCheckpoints;
    }
}
