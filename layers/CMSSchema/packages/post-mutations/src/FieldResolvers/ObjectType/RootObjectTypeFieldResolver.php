<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\PostMutations\MutationResolvers\CreatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableCreatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootCreatePostInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootUpdatePostInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
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
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?RootUpdatePostMutationPayloadObjectTypeResolver $rootUpdatePostMutationPayloadObjectTypeResolver = null;
    private ?RootCreatePostMutationPayloadObjectTypeResolver $rootCreatePostMutationPayloadObjectTypeResolver = null;
    private ?CreatePostMutationResolver $createPostMutationResolver = null;
    private ?UpdatePostMutationResolver $updatePostMutationResolver = null;
    private ?PayloadableUpdatePostMutationResolver $payloadableUpdatePostMutationResolver = null;
    private ?PayloadableCreatePostMutationResolver $payloadableCreatePostMutationResolver = null;
    private ?RootUpdatePostInputObjectTypeResolver $rootUpdatePostInputObjectTypeResolver = null;
    private ?RootCreatePostInputObjectTypeResolver $rootCreatePostInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setRootUpdatePostMutationPayloadObjectTypeResolver(RootUpdatePostMutationPayloadObjectTypeResolver $rootUpdatePostMutationPayloadObjectTypeResolver): void
    {
        $this->rootUpdatePostMutationPayloadObjectTypeResolver = $rootUpdatePostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePostMutationPayloadObjectTypeResolver(): RootUpdatePostMutationPayloadObjectTypeResolver
    {
        /** @var RootUpdatePostMutationPayloadObjectTypeResolver */
        return $this->rootUpdatePostMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(RootUpdatePostMutationPayloadObjectTypeResolver::class);
    }
    final public function setRootCreatePostMutationPayloadObjectTypeResolver(RootCreatePostMutationPayloadObjectTypeResolver $rootCreatePostMutationPayloadObjectTypeResolver): void
    {
        $this->rootCreatePostMutationPayloadObjectTypeResolver = $rootCreatePostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreatePostMutationPayloadObjectTypeResolver(): RootCreatePostMutationPayloadObjectTypeResolver
    {
        /** @var RootCreatePostMutationPayloadObjectTypeResolver */
        return $this->rootCreatePostMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(RootCreatePostMutationPayloadObjectTypeResolver::class);
    }
    final public function setCreatePostMutationResolver(CreatePostMutationResolver $createPostMutationResolver): void
    {
        $this->createPostMutationResolver = $createPostMutationResolver;
    }
    final protected function getCreatePostMutationResolver(): CreatePostMutationResolver
    {
        /** @var CreatePostMutationResolver */
        return $this->createPostMutationResolver ??= $this->instanceManager->getInstance(CreatePostMutationResolver::class);
    }
    final public function setUpdatePostMutationResolver(UpdatePostMutationResolver $updatePostMutationResolver): void
    {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }
    final protected function getUpdatePostMutationResolver(): UpdatePostMutationResolver
    {
        /** @var UpdatePostMutationResolver */
        return $this->updatePostMutationResolver ??= $this->instanceManager->getInstance(UpdatePostMutationResolver::class);
    }
    final public function setPayloadableUpdatePostMutationResolver(PayloadableUpdatePostMutationResolver $payloadableUpdatePostMutationResolver): void
    {
        $this->payloadableUpdatePostMutationResolver = $payloadableUpdatePostMutationResolver;
    }
    final protected function getPayloadableUpdatePostMutationResolver(): PayloadableUpdatePostMutationResolver
    {
        /** @var PayloadableUpdatePostMutationResolver */
        return $this->payloadableUpdatePostMutationResolver ??= $this->instanceManager->getInstance(PayloadableUpdatePostMutationResolver::class);
    }
    final public function setPayloadableCreatePostMutationResolver(PayloadableCreatePostMutationResolver $payloadableCreatePostMutationResolver): void
    {
        $this->payloadableCreatePostMutationResolver = $payloadableCreatePostMutationResolver;
    }
    final protected function getPayloadableCreatePostMutationResolver(): PayloadableCreatePostMutationResolver
    {
        /** @var PayloadableCreatePostMutationResolver */
        return $this->payloadableCreatePostMutationResolver ??= $this->instanceManager->getInstance(PayloadableCreatePostMutationResolver::class);
    }
    final public function setRootUpdatePostInputObjectTypeResolver(RootUpdatePostInputObjectTypeResolver $rootUpdatePostInputObjectTypeResolver): void
    {
        $this->rootUpdatePostInputObjectTypeResolver = $rootUpdatePostInputObjectTypeResolver;
    }
    final protected function getRootUpdatePostInputObjectTypeResolver(): RootUpdatePostInputObjectTypeResolver
    {
        /** @var RootUpdatePostInputObjectTypeResolver */
        return $this->rootUpdatePostInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootUpdatePostInputObjectTypeResolver::class);
    }
    final public function setRootCreatePostInputObjectTypeResolver(RootCreatePostInputObjectTypeResolver $rootCreatePostInputObjectTypeResolver): void
    {
        $this->rootCreatePostInputObjectTypeResolver = $rootCreatePostInputObjectTypeResolver;
    }
    final protected function getRootCreatePostInputObjectTypeResolver(): RootCreatePostInputObjectTypeResolver
    {
        /** @var RootCreatePostInputObjectTypeResolver */
        return $this->rootCreatePostInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootCreatePostInputObjectTypeResolver::class);
    }
    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        /** @var UserLoggedInCheckpoint */
        return $this->userLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
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
        $moduleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        return array_merge(
            [
                'createPost',
            ],
            !$moduleConfiguration->disableRedundantRootTypeMutationFields() ? [
                'updatePost',
            ] : []
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createPost' => $this->__('Create a post', 'post-mutations'),
            'updatePost' => $this->__('Update a post', 'post-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if (!$usePayloadableCustomPostMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return match ($fieldName) {
            'createPost',
            'updatePost' =>
                SchemaTypeModifiers::NON_NULLABLE,
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
            'updatePost' => [
                'input' => $this->getRootUpdatePostInputObjectTypeResolver(),
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
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'createPost' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableCreatePostMutationResolver()
                : $this->getCreatePostMutationResolver(),
            'updatePost' => $usePayloadableCustomPostMutations
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
        if ($usePayloadableCustomPostMutations) {
            return match ($fieldName) {
                'createPost' => $this->getRootCreatePostMutationPayloadObjectTypeResolver(),
                'updatePost' => $this->getRootUpdatePostMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createPost',
            'updatePost'
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
            case 'updatePost':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
