<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\PostUpdateInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostUpdateMutationPayloadObjectTypeResolver $postUpdateMutationPayloadObjectTypeResolver = null;
    private ?UpdatePostMutationResolver $updatePostMutationResolver = null;
    private ?PayloadableUpdatePostMutationResolver $payloadableUpdatePostMutationResolver = null;
    private ?PostUpdateInputObjectTypeResolver $postUpdateInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final public function setPostUpdateMutationPayloadObjectTypeResolver(PostUpdateMutationPayloadObjectTypeResolver $postUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->postUpdateMutationPayloadObjectTypeResolver = $postUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostUpdateMutationPayloadObjectTypeResolver(): PostUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->postUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var PostUpdateMutationPayloadObjectTypeResolver */
            $postUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostUpdateMutationPayloadObjectTypeResolver::class);
            $this->postUpdateMutationPayloadObjectTypeResolver = $postUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->postUpdateMutationPayloadObjectTypeResolver;
    }
    final public function setUpdatePostMutationResolver(UpdatePostMutationResolver $updatePostMutationResolver): void
    {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }
    final protected function getUpdatePostMutationResolver(): UpdatePostMutationResolver
    {
        if ($this->updatePostMutationResolver === null) {
            /** @var UpdatePostMutationResolver */
            $updatePostMutationResolver = $this->instanceManager->getInstance(UpdatePostMutationResolver::class);
            $this->updatePostMutationResolver = $updatePostMutationResolver;
        }
        return $this->updatePostMutationResolver;
    }
    final public function setPayloadableUpdatePostMutationResolver(PayloadableUpdatePostMutationResolver $payloadableUpdatePostMutationResolver): void
    {
        $this->payloadableUpdatePostMutationResolver = $payloadableUpdatePostMutationResolver;
    }
    final protected function getPayloadableUpdatePostMutationResolver(): PayloadableUpdatePostMutationResolver
    {
        if ($this->payloadableUpdatePostMutationResolver === null) {
            /** @var PayloadableUpdatePostMutationResolver */
            $payloadableUpdatePostMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePostMutationResolver::class);
            $this->payloadableUpdatePostMutationResolver = $payloadableUpdatePostMutationResolver;
        }
        return $this->payloadableUpdatePostMutationResolver;
    }
    final public function setPostUpdateInputObjectTypeResolver(PostUpdateInputObjectTypeResolver $postUpdateInputObjectTypeResolver): void
    {
        $this->postUpdateInputObjectTypeResolver = $postUpdateInputObjectTypeResolver;
    }
    final protected function getPostUpdateInputObjectTypeResolver(): PostUpdateInputObjectTypeResolver
    {
        if ($this->postUpdateInputObjectTypeResolver === null) {
            /** @var PostUpdateInputObjectTypeResolver */
            $postUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(PostUpdateInputObjectTypeResolver::class);
            $this->postUpdateInputObjectTypeResolver = $postUpdateInputObjectTypeResolver;
        }
        return $this->postUpdateInputObjectTypeResolver;
    }
    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
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
                'input' => $this->getPostUpdateInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdatePostMutationResolver()
                : $this->getUpdatePostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCustomPostMutations
                ? $this->getPostUpdateMutationPayloadObjectTypeResolver()
                : $this->getPostObjectTypeResolver(),
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

        /**
         * For Payloadable: The "User Logged-in" checkpoint validation is not added,
         * instead this validation is executed inside the mutation, so the error
         * shows up in the Payload
         *
         * @var CustomPostMutationsModuleConfiguration
         */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if ($usePayloadableCustomPostMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'update':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
