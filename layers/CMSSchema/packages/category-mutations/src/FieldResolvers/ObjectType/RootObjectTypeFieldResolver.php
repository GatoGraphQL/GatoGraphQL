<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\Module;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration;
use PoPCMSSchema\CategoryMutations\MutationResolvers\CreateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\CreateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableCreateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableCreateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\UpdateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\UpdateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootCreateGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootUpdateGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
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

    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver $rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver = null;
    private ?RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver $rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver = null;
    private ?CreateGenericCategoryTermMutationResolver $createGenericCategoryTermMutationResolver = null;
    private ?CreateGenericCategoryTermBulkOperationMutationResolver $createGenericCategoryTermBulkOperationMutationResolver = null;
    private ?UpdateGenericCategoryTermMutationResolver $updateGenericCategoryTermMutationResolver = null;
    private ?UpdateGenericCategoryTermBulkOperationMutationResolver $updateGenericCategoryTermBulkOperationMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMutationResolver $payloadableUpdateGenericCategoryTermMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver $payloadableUpdateGenericCategoryTermBulkOperationMutationResolver = null;
    private ?PayloadableCreateGenericCategoryTermMutationResolver $payloadableCreateGenericCategoryTermMutationResolver = null;
    private ?PayloadableCreateGenericCategoryTermBulkOperationMutationResolver $payloadableCreateGenericCategoryTermBulkOperationMutationResolver = null;
    private ?RootUpdateGenericCategoryTermInputObjectTypeResolver $rootUpdateGenericCategoryTermInputObjectTypeResolver = null;
    private ?RootCreateGenericCategoryTermInputObjectTypeResolver $rootCreateGenericCategoryTermInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setGenericCategoryObjectTypeResolver(GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver): void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final public function setRootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver(RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver $rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver): void
    {
        $this->rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver = $rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver(): RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver */
            $rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver = $rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver;
    }
    final public function setRootCreateGenericCategoryTermMutationPayloadObjectTypeResolver(RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver $rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver): void
    {
        $this->rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver = $rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreateGenericCategoryTermMutationPayloadObjectTypeResolver(): RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver */
            $rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver::class);
            $this->rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver = $rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver;
    }
    final public function setCreateGenericCategoryTermMutationResolver(CreateGenericCategoryTermMutationResolver $createGenericCategoryTermMutationResolver): void
    {
        $this->createGenericCategoryTermMutationResolver = $createGenericCategoryTermMutationResolver;
    }
    final protected function getCreateGenericCategoryTermMutationResolver(): CreateGenericCategoryTermMutationResolver
    {
        if ($this->createGenericCategoryTermMutationResolver === null) {
            /** @var CreateGenericCategoryTermMutationResolver */
            $createGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryTermMutationResolver::class);
            $this->createGenericCategoryTermMutationResolver = $createGenericCategoryTermMutationResolver;
        }
        return $this->createGenericCategoryTermMutationResolver;
    }
    final public function setCreateGenericCategoryTermBulkOperationMutationResolver(CreateGenericCategoryTermBulkOperationMutationResolver $createGenericCategoryTermBulkOperationMutationResolver): void
    {
        $this->createGenericCategoryTermBulkOperationMutationResolver = $createGenericCategoryTermBulkOperationMutationResolver;
    }
    final protected function getCreateGenericCategoryTermBulkOperationMutationResolver(): CreateGenericCategoryTermBulkOperationMutationResolver
    {
        if ($this->createGenericCategoryTermBulkOperationMutationResolver === null) {
            /** @var CreateGenericCategoryTermBulkOperationMutationResolver */
            $createGenericCategoryTermBulkOperationMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryTermBulkOperationMutationResolver::class);
            $this->createGenericCategoryTermBulkOperationMutationResolver = $createGenericCategoryTermBulkOperationMutationResolver;
        }
        return $this->createGenericCategoryTermBulkOperationMutationResolver;
    }
    final public function setUpdateGenericCategoryTermMutationResolver(UpdateGenericCategoryTermMutationResolver $updateGenericCategoryTermMutationResolver): void
    {
        $this->updateGenericCategoryTermMutationResolver = $updateGenericCategoryTermMutationResolver;
    }
    final protected function getUpdateGenericCategoryTermMutationResolver(): UpdateGenericCategoryTermMutationResolver
    {
        if ($this->updateGenericCategoryTermMutationResolver === null) {
            /** @var UpdateGenericCategoryTermMutationResolver */
            $updateGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryTermMutationResolver::class);
            $this->updateGenericCategoryTermMutationResolver = $updateGenericCategoryTermMutationResolver;
        }
        return $this->updateGenericCategoryTermMutationResolver;
    }
    final public function setUpdateGenericCategoryTermBulkOperationMutationResolver(UpdateGenericCategoryTermBulkOperationMutationResolver $updateGenericCategoryTermBulkOperationMutationResolver): void
    {
        $this->updateGenericCategoryTermBulkOperationMutationResolver = $updateGenericCategoryTermBulkOperationMutationResolver;
    }
    final protected function getUpdateGenericCategoryTermBulkOperationMutationResolver(): UpdateGenericCategoryTermBulkOperationMutationResolver
    {
        if ($this->updateGenericCategoryTermBulkOperationMutationResolver === null) {
            /** @var UpdateGenericCategoryTermBulkOperationMutationResolver */
            $updateGenericCategoryTermBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryTermBulkOperationMutationResolver::class);
            $this->updateGenericCategoryTermBulkOperationMutationResolver = $updateGenericCategoryTermBulkOperationMutationResolver;
        }
        return $this->updateGenericCategoryTermBulkOperationMutationResolver;
    }
    final public function setPayloadableUpdateGenericCategoryTermMutationResolver(PayloadableUpdateGenericCategoryTermMutationResolver $payloadableUpdateGenericCategoryTermMutationResolver): void
    {
        $this->payloadableUpdateGenericCategoryTermMutationResolver = $payloadableUpdateGenericCategoryTermMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryTermMutationResolver(): PayloadableUpdateGenericCategoryTermMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermMutationResolver */
            $payloadableUpdateGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermMutationResolver = $payloadableUpdateGenericCategoryTermMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermMutationResolver;
    }
    final public function setPayloadableUpdateGenericCategoryTermBulkOperationMutationResolver(PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver $payloadableUpdateGenericCategoryTermBulkOperationMutationResolver): void
    {
        $this->payloadableUpdateGenericCategoryTermBulkOperationMutationResolver = $payloadableUpdateGenericCategoryTermBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryTermBulkOperationMutationResolver(): PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver */
            $payloadableUpdateGenericCategoryTermBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermBulkOperationMutationResolver = $payloadableUpdateGenericCategoryTermBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermBulkOperationMutationResolver;
    }
    final public function setPayloadableCreateGenericCategoryTermMutationResolver(PayloadableCreateGenericCategoryTermMutationResolver $payloadableCreateGenericCategoryTermMutationResolver): void
    {
        $this->payloadableCreateGenericCategoryTermMutationResolver = $payloadableCreateGenericCategoryTermMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryTermMutationResolver(): PayloadableCreateGenericCategoryTermMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryTermMutationResolver */
            $payloadableCreateGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryTermMutationResolver::class);
            $this->payloadableCreateGenericCategoryTermMutationResolver = $payloadableCreateGenericCategoryTermMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryTermMutationResolver;
    }
    final public function setPayloadableCreateGenericCategoryTermBulkOperationMutationResolver(PayloadableCreateGenericCategoryTermBulkOperationMutationResolver $payloadableCreateGenericCategoryTermBulkOperationMutationResolver): void
    {
        $this->payloadableCreateGenericCategoryTermBulkOperationMutationResolver = $payloadableCreateGenericCategoryTermBulkOperationMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryTermBulkOperationMutationResolver(): PayloadableCreateGenericCategoryTermBulkOperationMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryTermBulkOperationMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryTermBulkOperationMutationResolver */
            $payloadableCreateGenericCategoryTermBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryTermBulkOperationMutationResolver::class);
            $this->payloadableCreateGenericCategoryTermBulkOperationMutationResolver = $payloadableCreateGenericCategoryTermBulkOperationMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryTermBulkOperationMutationResolver;
    }
    final public function setRootUpdateGenericCategoryTermInputObjectTypeResolver(RootUpdateGenericCategoryTermInputObjectTypeResolver $rootUpdateGenericCategoryTermInputObjectTypeResolver): void
    {
        $this->rootUpdateGenericCategoryTermInputObjectTypeResolver = $rootUpdateGenericCategoryTermInputObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryTermInputObjectTypeResolver(): RootUpdateGenericCategoryTermInputObjectTypeResolver
    {
        if ($this->rootUpdateGenericCategoryTermInputObjectTypeResolver === null) {
            /** @var RootUpdateGenericCategoryTermInputObjectTypeResolver */
            $rootUpdateGenericCategoryTermInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermInputObjectTypeResolver::class);
            $this->rootUpdateGenericCategoryTermInputObjectTypeResolver = $rootUpdateGenericCategoryTermInputObjectTypeResolver;
        }
        return $this->rootUpdateGenericCategoryTermInputObjectTypeResolver;
    }
    final public function setRootCreateGenericCategoryTermInputObjectTypeResolver(RootCreateGenericCategoryTermInputObjectTypeResolver $rootCreateGenericCategoryTermInputObjectTypeResolver): void
    {
        $this->rootCreateGenericCategoryTermInputObjectTypeResolver = $rootCreateGenericCategoryTermInputObjectTypeResolver;
    }
    final protected function getRootCreateGenericCategoryTermInputObjectTypeResolver(): RootCreateGenericCategoryTermInputObjectTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermInputObjectTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermInputObjectTypeResolver */
            $rootCreateGenericCategoryTermInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermInputObjectTypeResolver::class);
            $this->rootCreateGenericCategoryTermInputObjectTypeResolver = $rootCreateGenericCategoryTermInputObjectTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermInputObjectTypeResolver;
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableCategoryMutations = $moduleConfiguration->addFieldsToQueryPayloadableCategoryMutations();
        return array_merge(
            [
                'createCategory',
                'createCategorys',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updateCategory',
                'updateCategorys',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMutations ? [
                'createCategoryMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMutations && !$disableRedundantRootTypeMutationFields ? [
                'updateCategoryMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createCategory' => $this->__('Create a category. This mutation accepts the data that is common to all categories (title, content, excerpt, slug, etc), but no custom data (such as the price of a Product CPT). So use it with care, only for those category types that do not require to be provided data for their own custom fields (for those, you will need to use a more specific mutation, for that CPT)', 'category-mutations'),
            'createCategorys' => $this->__('Create categories. This mutation accepts the data that is common to all categories (title, content, excerpt, slug, etc), but no custom data (such as the price of a Product CPT). So use it with care, only for those category types that do not require to be provided data for their own custom fields (for those, you will need to use a more specific mutation, for that CPT)', 'category-mutations'),
            'updateCategory' => $this->__('Update a category', 'category-mutations'),
            'updateCategorys' => $this->__('Update categories', 'category-mutations'),
            'createCategoryMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createCategory` mutation', 'category-mutations'),
            'updateCategoryMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCategory` mutation', 'category-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        if (!$usePayloadableCategoryMutations) {
            return match ($fieldName) {
                'createCategory',
                'updateCategory'
                    => SchemaTypeModifiers::NONE,
                'createCategorys',
                'updateCategorys'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createCategoryMutationPayloadObjects',
            'updateCategoryMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createCategory',
            'updateCategory'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createCategorys',
            'updateCategorys'
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
            'createCategory' => [
                'input' => $this->getRootCreateGenericCategoryTermInputObjectTypeResolver(),
            ],
            'createCategorys'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreateGenericCategoryTermInputObjectTypeResolver()),
            'updateCategory' => [
                'input' => $this->getRootUpdateGenericCategoryTermInputObjectTypeResolver(),
            ],
            'updateCategorys'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateGenericCategoryTermInputObjectTypeResolver()),
            'createCategoryMutationPayloadObjects',
            'updateCategoryMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'createCategoryMutationPayloadObjects',
            'updateCategoryMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createCategorys',
            'updateCategorys',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createCategory' => 'input'],
            ['updateCategory' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createCategorys',
            'updateCategorys',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        return match ($fieldName) {
            'createCategory' => $usePayloadableCategoryMutations
                ? $this->getPayloadableCreateGenericCategoryTermMutationResolver()
                : $this->getCreateGenericCategoryTermMutationResolver(),
            'createCategorys' => $usePayloadableCategoryMutations
                ? $this->getPayloadableCreateGenericCategoryTermBulkOperationMutationResolver()
                : $this->getCreateGenericCategoryTermBulkOperationMutationResolver(),
            'updateCategory' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMutationResolver()
                : $this->getUpdateGenericCategoryTermMutationResolver(),
            'updateCategorys' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermBulkOperationMutationResolver()
                : $this->getUpdateGenericCategoryTermBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        if ($usePayloadableCategoryMutations) {
            return match ($fieldName) {
                'createCategory',
                'createCategorys',
                'createCategoryMutationPayloadObjects'
                    => $this->getRootCreateGenericCategoryTermMutationPayloadObjectTypeResolver(),
                'updateCategory',
                'updateCategorys',
                'updateCategoryMutationPayloadObjects'
                    => $this->getRootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createCategory',
            'createCategorys',
            'updateCategory',
            'updateCategorys'
                => $this->getGenericCategoryObjectTypeResolver(),
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
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        if ($usePayloadableCategoryMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'createCategory':
            case 'createCategorys':
            case 'updateCategory':
            case 'updateCategorys':
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
            case 'createCategoryMutationPayloadObjects':
            case 'updateCategoryMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
