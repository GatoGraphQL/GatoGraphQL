<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\PostMutations\MutationResolvers\CreatePostBulkOperationMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\CreatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableCreatePostBulkOperationMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableCreatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostBulkOperationMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostBulkOperationMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootCreatePostInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootUpdatePostInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\BulkOperationDecoratorObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?RootUpdatePostMutationPayloadObjectTypeResolver $rootUpdatePostMutationPayloadObjectTypeResolver = null;
    private ?RootCreatePostMutationPayloadObjectTypeResolver $rootCreatePostMutationPayloadObjectTypeResolver = null;
    private ?CreatePostMutationResolver $createPostMutationResolver = null;
    private ?CreatePostBulkOperationMutationResolver $createPostBulkOperationMutationResolver = null;
    private ?UpdatePostMutationResolver $updatePostMutationResolver = null;
    private ?UpdatePostBulkOperationMutationResolver $updatePostBulkOperationMutationResolver = null;
    private ?PayloadableUpdatePostMutationResolver $payloadableUpdatePostMutationResolver = null;
    private ?PayloadableUpdatePostBulkOperationMutationResolver $payloadableUpdatePostBulkOperationMutationResolver = null;
    private ?PayloadableCreatePostMutationResolver $payloadableCreatePostMutationResolver = null;
    private ?PayloadableCreatePostBulkOperationMutationResolver $payloadableCreatePostBulkOperationMutationResolver = null;
    private ?RootUpdatePostInputObjectTypeResolver $rootUpdatePostInputObjectTypeResolver = null;
    private ?RootCreatePostInputObjectTypeResolver $rootCreatePostInputObjectTypeResolver = null;
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
    final public function setRootUpdatePostMutationPayloadObjectTypeResolver(RootUpdatePostMutationPayloadObjectTypeResolver $rootUpdatePostMutationPayloadObjectTypeResolver): void
    {
        $this->rootUpdatePostMutationPayloadObjectTypeResolver = $rootUpdatePostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePostMutationPayloadObjectTypeResolver(): RootUpdatePostMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePostMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePostMutationPayloadObjectTypeResolver */
            $rootUpdatePostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePostMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePostMutationPayloadObjectTypeResolver = $rootUpdatePostMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePostMutationPayloadObjectTypeResolver;
    }
    final public function setRootCreatePostMutationPayloadObjectTypeResolver(RootCreatePostMutationPayloadObjectTypeResolver $rootCreatePostMutationPayloadObjectTypeResolver): void
    {
        $this->rootCreatePostMutationPayloadObjectTypeResolver = $rootCreatePostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreatePostMutationPayloadObjectTypeResolver(): RootCreatePostMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreatePostMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreatePostMutationPayloadObjectTypeResolver */
            $rootCreatePostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreatePostMutationPayloadObjectTypeResolver::class);
            $this->rootCreatePostMutationPayloadObjectTypeResolver = $rootCreatePostMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreatePostMutationPayloadObjectTypeResolver;
    }
    final public function setCreatePostMutationResolver(CreatePostMutationResolver $createPostMutationResolver): void
    {
        $this->createPostMutationResolver = $createPostMutationResolver;
    }
    final protected function getCreatePostMutationResolver(): CreatePostMutationResolver
    {
        if ($this->createPostMutationResolver === null) {
            /** @var CreatePostMutationResolver */
            $createPostMutationResolver = $this->instanceManager->getInstance(CreatePostMutationResolver::class);
            $this->createPostMutationResolver = $createPostMutationResolver;
        }
        return $this->createPostMutationResolver;
    }
    final public function setCreatePostBulkOperationMutationResolver(CreatePostBulkOperationMutationResolver $createPostBulkOperationMutationResolver): void
    {
        $this->createPostBulkOperationMutationResolver = $createPostBulkOperationMutationResolver;
    }
    final protected function getCreatePostBulkOperationMutationResolver(): CreatePostBulkOperationMutationResolver
    {
        if ($this->createPostBulkOperationMutationResolver === null) {
            /** @var CreatePostBulkOperationMutationResolver */
            $createPostBulkOperationMutationResolver = $this->instanceManager->getInstance(CreatePostBulkOperationMutationResolver::class);
            $this->createPostBulkOperationMutationResolver = $createPostBulkOperationMutationResolver;
        }
        return $this->createPostBulkOperationMutationResolver;
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
    final public function setUpdatePostBulkOperationMutationResolver(UpdatePostBulkOperationMutationResolver $updatePostBulkOperationMutationResolver): void
    {
        $this->updatePostBulkOperationMutationResolver = $updatePostBulkOperationMutationResolver;
    }
    final protected function getUpdatePostBulkOperationMutationResolver(): UpdatePostBulkOperationMutationResolver
    {
        if ($this->updatePostBulkOperationMutationResolver === null) {
            /** @var UpdatePostBulkOperationMutationResolver */
            $updatePostBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdatePostBulkOperationMutationResolver::class);
            $this->updatePostBulkOperationMutationResolver = $updatePostBulkOperationMutationResolver;
        }
        return $this->updatePostBulkOperationMutationResolver;
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
    final public function setPayloadableUpdatePostBulkOperationMutationResolver(PayloadableUpdatePostBulkOperationMutationResolver $payloadableUpdatePostBulkOperationMutationResolver): void
    {
        $this->payloadableUpdatePostBulkOperationMutationResolver = $payloadableUpdatePostBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdatePostBulkOperationMutationResolver(): PayloadableUpdatePostBulkOperationMutationResolver
    {
        if ($this->payloadableUpdatePostBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdatePostBulkOperationMutationResolver */
            $payloadableUpdatePostBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePostBulkOperationMutationResolver::class);
            $this->payloadableUpdatePostBulkOperationMutationResolver = $payloadableUpdatePostBulkOperationMutationResolver;
        }
        return $this->payloadableUpdatePostBulkOperationMutationResolver;
    }
    final public function setPayloadableCreatePostMutationResolver(PayloadableCreatePostMutationResolver $payloadableCreatePostMutationResolver): void
    {
        $this->payloadableCreatePostMutationResolver = $payloadableCreatePostMutationResolver;
    }
    final protected function getPayloadableCreatePostMutationResolver(): PayloadableCreatePostMutationResolver
    {
        if ($this->payloadableCreatePostMutationResolver === null) {
            /** @var PayloadableCreatePostMutationResolver */
            $payloadableCreatePostMutationResolver = $this->instanceManager->getInstance(PayloadableCreatePostMutationResolver::class);
            $this->payloadableCreatePostMutationResolver = $payloadableCreatePostMutationResolver;
        }
        return $this->payloadableCreatePostMutationResolver;
    }
    final public function setPayloadableCreatePostBulkOperationMutationResolver(PayloadableCreatePostBulkOperationMutationResolver $payloadableCreatePostBulkOperationMutationResolver): void
    {
        $this->payloadableCreatePostBulkOperationMutationResolver = $payloadableCreatePostBulkOperationMutationResolver;
    }
    final protected function getPayloadableCreatePostBulkOperationMutationResolver(): PayloadableCreatePostBulkOperationMutationResolver
    {
        if ($this->payloadableCreatePostBulkOperationMutationResolver === null) {
            /** @var PayloadableCreatePostBulkOperationMutationResolver */
            $payloadableCreatePostBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreatePostBulkOperationMutationResolver::class);
            $this->payloadableCreatePostBulkOperationMutationResolver = $payloadableCreatePostBulkOperationMutationResolver;
        }
        return $this->payloadableCreatePostBulkOperationMutationResolver;
    }
    final public function setRootUpdatePostInputObjectTypeResolver(RootUpdatePostInputObjectTypeResolver $rootUpdatePostInputObjectTypeResolver): void
    {
        $this->rootUpdatePostInputObjectTypeResolver = $rootUpdatePostInputObjectTypeResolver;
    }
    final protected function getRootUpdatePostInputObjectTypeResolver(): RootUpdatePostInputObjectTypeResolver
    {
        if ($this->rootUpdatePostInputObjectTypeResolver === null) {
            /** @var RootUpdatePostInputObjectTypeResolver */
            $rootUpdatePostInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePostInputObjectTypeResolver::class);
            $this->rootUpdatePostInputObjectTypeResolver = $rootUpdatePostInputObjectTypeResolver;
        }
        return $this->rootUpdatePostInputObjectTypeResolver;
    }
    final public function setRootCreatePostInputObjectTypeResolver(RootCreatePostInputObjectTypeResolver $rootCreatePostInputObjectTypeResolver): void
    {
        $this->rootCreatePostInputObjectTypeResolver = $rootCreatePostInputObjectTypeResolver;
    }
    final protected function getRootCreatePostInputObjectTypeResolver(): RootCreatePostInputObjectTypeResolver
    {
        if ($this->rootCreatePostInputObjectTypeResolver === null) {
            /** @var RootCreatePostInputObjectTypeResolver */
            $rootCreatePostInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreatePostInputObjectTypeResolver::class);
            $this->rootCreatePostInputObjectTypeResolver = $rootCreatePostInputObjectTypeResolver;
        }
        return $this->rootCreatePostInputObjectTypeResolver;
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
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        $disableRedundantRootTypeMutationFields = $engineModuleConfiguration->disableRedundantRootTypeMutationFields();
        /** @var CustomPostMutationsModuleConfiguration */
        $customPostMutationsModuleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $addFieldsToQueryPayloadableCustomPostMutations = $customPostMutationsModuleConfiguration->addFieldsToQueryPayloadableCustomPostMutations();
        return array_merge(
            [
                'createPost',
                'createPosts',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updatePost',
                'updatePosts',
            ] : [],
            $addFieldsToQueryPayloadableCustomPostMutations ? [
                'createPostMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableCustomPostMutations && !$disableRedundantRootTypeMutationFields ? [
                'updatePostMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createPost' => $this->__('Create a post', 'post-mutations'),
            'createPosts' => $this->__('Create posts', 'post-mutations'),
            'updatePost' => $this->__('Update a post', 'post-mutations'),
            'updatePosts' => $this->__('Update posts', 'post-mutations'),
            'createPostMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createPost` mutation', 'post-mutations'),
            'updatePostMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updatePost` mutation', 'post-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if (!$usePayloadableCustomPostMutations) {
            return match ($fieldName) {
                'createPost',
                'updatePost'
                    => SchemaTypeModifiers::NONE,
                'createPosts',
                'updatePosts'
                    => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createPostMutationPayloadObjects',
            'updatePostMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createPost',
            'updatePost'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createPosts',
            'updatePosts'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'createPost' => [
                'input' => $this->getRootCreatePostInputObjectTypeResolver(),
            ],
            'createPosts'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreatePostInputObjectTypeResolver()),
            'updatePost' => [
                'input' => $this->getRootUpdatePostInputObjectTypeResolver(),
            ],
            'updatePosts'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdatePostInputObjectTypeResolver()),
            'createPostMutationPayloadObjects',
            'updatePostMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'createPostMutationPayloadObjects',
            'updatePostMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createPosts',
            'updatePosts',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createPost' => 'input'],
            ['updatePost' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createPosts',
            'updatePosts',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'createPost' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableCreatePostMutationResolver()
                : $this->getCreatePostMutationResolver(),
            'createPosts' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableCreatePostBulkOperationMutationResolver()
                : $this->getCreatePostBulkOperationMutationResolver(),
            'updatePost' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdatePostMutationResolver()
                : $this->getUpdatePostMutationResolver(),
            'updatePosts' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdatePostBulkOperationMutationResolver()
                : $this->getUpdatePostBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if ($usePayloadableCustomPostMutations) {
            return match ($fieldName) {
                'createPost',
                'createPosts',
                'createPostMutationPayloadObjects'
                    => $this->getRootCreatePostMutationPayloadObjectTypeResolver(),
                'updatePost',
                'updatePosts',
                'updatePostMutationPayloadObjects'
                    => $this->getRootUpdatePostMutationPayloadObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createPost',
            'createPosts',
            'updatePost',
            'updatePosts'
                => $this->getPostObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
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
            case 'createPost':
            case 'createPosts':
            case 'updatePost':
            case 'updatePosts':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'createPostMutationPayloadObjects':
            case 'updatePostMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
