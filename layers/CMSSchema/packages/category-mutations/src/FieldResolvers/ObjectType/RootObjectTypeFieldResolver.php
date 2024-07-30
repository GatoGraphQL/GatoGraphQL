<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\Module;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration;
use PoPCMSSchema\CategoryMutations\MutationResolvers\CreateGenericCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\CreateGenericCategoryMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableCreateGenericCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableCreateGenericCategoryMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableUpdateGenericCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableUpdateGenericCategoryMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\UpdateGenericCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMutations\MutationResolvers\UpdateGenericCategoryMutationResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootCreateGenericCategoryInputObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootUpdateGenericCategoryInputObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryMutationPayloadObjectTypeResolver;
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
    private ?RootUpdateGenericCategoryMutationPayloadObjectTypeResolver $rootUpdateGenericCategoryMutationPayloadObjectTypeResolver = null;
    private ?RootCreateGenericCategoryMutationPayloadObjectTypeResolver $rootCreateGenericCategoryMutationPayloadObjectTypeResolver = null;
    private ?CreateGenericCategoryMutationResolver $createGenericCategoryMutationResolver = null;
    private ?CreateGenericCategoryBulkOperationMutationResolver $createGenericCategoryBulkOperationMutationResolver = null;
    private ?UpdateGenericCategoryMutationResolver $updateGenericCategoryMutationResolver = null;
    private ?UpdateGenericCategoryBulkOperationMutationResolver $updateGenericCategoryBulkOperationMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryMutationResolver $payloadableUpdateGenericCategoryMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryBulkOperationMutationResolver $payloadableUpdateGenericCategoryBulkOperationMutationResolver = null;
    private ?PayloadableCreateGenericCategoryMutationResolver $payloadableCreateGenericCategoryMutationResolver = null;
    private ?PayloadableCreateGenericCategoryBulkOperationMutationResolver $payloadableCreateGenericCategoryBulkOperationMutationResolver = null;
    private ?RootUpdateGenericCategoryInputObjectTypeResolver $rootUpdateGenericCategoryInputObjectTypeResolver = null;
    private ?RootCreateGenericCategoryInputObjectTypeResolver $rootCreateGenericCategoryInputObjectTypeResolver = null;
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
    final public function setRootUpdateGenericCategoryMutationPayloadObjectTypeResolver(RootUpdateGenericCategoryMutationPayloadObjectTypeResolver $rootUpdateGenericCategoryMutationPayloadObjectTypeResolver): void
    {
        $this->rootUpdateGenericCategoryMutationPayloadObjectTypeResolver = $rootUpdateGenericCategoryMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryMutationPayloadObjectTypeResolver(): RootUpdateGenericCategoryMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericCategoryMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericCategoryMutationPayloadObjectTypeResolver */
            $rootUpdateGenericCategoryMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericCategoryMutationPayloadObjectTypeResolver = $rootUpdateGenericCategoryMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericCategoryMutationPayloadObjectTypeResolver;
    }
    final public function setRootCreateGenericCategoryMutationPayloadObjectTypeResolver(RootCreateGenericCategoryMutationPayloadObjectTypeResolver $rootCreateGenericCategoryMutationPayloadObjectTypeResolver): void
    {
        $this->rootCreateGenericCategoryMutationPayloadObjectTypeResolver = $rootCreateGenericCategoryMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreateGenericCategoryMutationPayloadObjectTypeResolver(): RootCreateGenericCategoryMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateGenericCategoryMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateGenericCategoryMutationPayloadObjectTypeResolver */
            $rootCreateGenericCategoryMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryMutationPayloadObjectTypeResolver::class);
            $this->rootCreateGenericCategoryMutationPayloadObjectTypeResolver = $rootCreateGenericCategoryMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateGenericCategoryMutationPayloadObjectTypeResolver;
    }
    final public function setCreateGenericCategoryMutationResolver(CreateGenericCategoryMutationResolver $createGenericCategoryMutationResolver): void
    {
        $this->createGenericCategoryMutationResolver = $createGenericCategoryMutationResolver;
    }
    final protected function getCreateGenericCategoryMutationResolver(): CreateGenericCategoryMutationResolver
    {
        if ($this->createGenericCategoryMutationResolver === null) {
            /** @var CreateGenericCategoryMutationResolver */
            $createGenericCategoryMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryMutationResolver::class);
            $this->createGenericCategoryMutationResolver = $createGenericCategoryMutationResolver;
        }
        return $this->createGenericCategoryMutationResolver;
    }
    final public function setCreateGenericCategoryBulkOperationMutationResolver(CreateGenericCategoryBulkOperationMutationResolver $createGenericCategoryBulkOperationMutationResolver): void
    {
        $this->createGenericCategoryBulkOperationMutationResolver = $createGenericCategoryBulkOperationMutationResolver;
    }
    final protected function getCreateGenericCategoryBulkOperationMutationResolver(): CreateGenericCategoryBulkOperationMutationResolver
    {
        if ($this->createGenericCategoryBulkOperationMutationResolver === null) {
            /** @var CreateGenericCategoryBulkOperationMutationResolver */
            $createGenericCategoryBulkOperationMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryBulkOperationMutationResolver::class);
            $this->createGenericCategoryBulkOperationMutationResolver = $createGenericCategoryBulkOperationMutationResolver;
        }
        return $this->createGenericCategoryBulkOperationMutationResolver;
    }
    final public function setUpdateGenericCategoryMutationResolver(UpdateGenericCategoryMutationResolver $updateGenericCategoryMutationResolver): void
    {
        $this->updateGenericCategoryMutationResolver = $updateGenericCategoryMutationResolver;
    }
    final protected function getUpdateGenericCategoryMutationResolver(): UpdateGenericCategoryMutationResolver
    {
        if ($this->updateGenericCategoryMutationResolver === null) {
            /** @var UpdateGenericCategoryMutationResolver */
            $updateGenericCategoryMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryMutationResolver::class);
            $this->updateGenericCategoryMutationResolver = $updateGenericCategoryMutationResolver;
        }
        return $this->updateGenericCategoryMutationResolver;
    }
    final public function setUpdateGenericCategoryBulkOperationMutationResolver(UpdateGenericCategoryBulkOperationMutationResolver $updateGenericCategoryBulkOperationMutationResolver): void
    {
        $this->updateGenericCategoryBulkOperationMutationResolver = $updateGenericCategoryBulkOperationMutationResolver;
    }
    final protected function getUpdateGenericCategoryBulkOperationMutationResolver(): UpdateGenericCategoryBulkOperationMutationResolver
    {
        if ($this->updateGenericCategoryBulkOperationMutationResolver === null) {
            /** @var UpdateGenericCategoryBulkOperationMutationResolver */
            $updateGenericCategoryBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryBulkOperationMutationResolver::class);
            $this->updateGenericCategoryBulkOperationMutationResolver = $updateGenericCategoryBulkOperationMutationResolver;
        }
        return $this->updateGenericCategoryBulkOperationMutationResolver;
    }
    final public function setPayloadableUpdateGenericCategoryMutationResolver(PayloadableUpdateGenericCategoryMutationResolver $payloadableUpdateGenericCategoryMutationResolver): void
    {
        $this->payloadableUpdateGenericCategoryMutationResolver = $payloadableUpdateGenericCategoryMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryMutationResolver(): PayloadableUpdateGenericCategoryMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryMutationResolver */
            $payloadableUpdateGenericCategoryMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryMutationResolver::class);
            $this->payloadableUpdateGenericCategoryMutationResolver = $payloadableUpdateGenericCategoryMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryMutationResolver;
    }
    final public function setPayloadableUpdateGenericCategoryBulkOperationMutationResolver(PayloadableUpdateGenericCategoryBulkOperationMutationResolver $payloadableUpdateGenericCategoryBulkOperationMutationResolver): void
    {
        $this->payloadableUpdateGenericCategoryBulkOperationMutationResolver = $payloadableUpdateGenericCategoryBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryBulkOperationMutationResolver(): PayloadableUpdateGenericCategoryBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryBulkOperationMutationResolver */
            $payloadableUpdateGenericCategoryBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryBulkOperationMutationResolver::class);
            $this->payloadableUpdateGenericCategoryBulkOperationMutationResolver = $payloadableUpdateGenericCategoryBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryBulkOperationMutationResolver;
    }
    final public function setPayloadableCreateGenericCategoryMutationResolver(PayloadableCreateGenericCategoryMutationResolver $payloadableCreateGenericCategoryMutationResolver): void
    {
        $this->payloadableCreateGenericCategoryMutationResolver = $payloadableCreateGenericCategoryMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryMutationResolver(): PayloadableCreateGenericCategoryMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryMutationResolver */
            $payloadableCreateGenericCategoryMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryMutationResolver::class);
            $this->payloadableCreateGenericCategoryMutationResolver = $payloadableCreateGenericCategoryMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryMutationResolver;
    }
    final public function setPayloadableCreateGenericCategoryBulkOperationMutationResolver(PayloadableCreateGenericCategoryBulkOperationMutationResolver $payloadableCreateGenericCategoryBulkOperationMutationResolver): void
    {
        $this->payloadableCreateGenericCategoryBulkOperationMutationResolver = $payloadableCreateGenericCategoryBulkOperationMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryBulkOperationMutationResolver(): PayloadableCreateGenericCategoryBulkOperationMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryBulkOperationMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryBulkOperationMutationResolver */
            $payloadableCreateGenericCategoryBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryBulkOperationMutationResolver::class);
            $this->payloadableCreateGenericCategoryBulkOperationMutationResolver = $payloadableCreateGenericCategoryBulkOperationMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryBulkOperationMutationResolver;
    }
    final public function setRootUpdateGenericCategoryInputObjectTypeResolver(RootUpdateGenericCategoryInputObjectTypeResolver $rootUpdateGenericCategoryInputObjectTypeResolver): void
    {
        $this->rootUpdateGenericCategoryInputObjectTypeResolver = $rootUpdateGenericCategoryInputObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryInputObjectTypeResolver(): RootUpdateGenericCategoryInputObjectTypeResolver
    {
        if ($this->rootUpdateGenericCategoryInputObjectTypeResolver === null) {
            /** @var RootUpdateGenericCategoryInputObjectTypeResolver */
            $rootUpdateGenericCategoryInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryInputObjectTypeResolver::class);
            $this->rootUpdateGenericCategoryInputObjectTypeResolver = $rootUpdateGenericCategoryInputObjectTypeResolver;
        }
        return $this->rootUpdateGenericCategoryInputObjectTypeResolver;
    }
    final public function setRootCreateGenericCategoryInputObjectTypeResolver(RootCreateGenericCategoryInputObjectTypeResolver $rootCreateGenericCategoryInputObjectTypeResolver): void
    {
        $this->rootCreateGenericCategoryInputObjectTypeResolver = $rootCreateGenericCategoryInputObjectTypeResolver;
    }
    final protected function getRootCreateGenericCategoryInputObjectTypeResolver(): RootCreateGenericCategoryInputObjectTypeResolver
    {
        if ($this->rootCreateGenericCategoryInputObjectTypeResolver === null) {
            /** @var RootCreateGenericCategoryInputObjectTypeResolver */
            $rootCreateGenericCategoryInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryInputObjectTypeResolver::class);
            $this->rootCreateGenericCategoryInputObjectTypeResolver = $rootCreateGenericCategoryInputObjectTypeResolver;
        }
        return $this->rootCreateGenericCategoryInputObjectTypeResolver;
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
            'createCategory' => $this->__('Create a category. This mutation accepts the data that is common to all categorys (title, content, excerpt, slug, etc), but no custom data (such as the price of a Product CPT). So use it with care, only for those category types that do not require to be provided data for their own custom fields (for those, you will need to use a more specific mutation, for that CPT)', 'category-mutations'),
            'createCategorys' => $this->__('Create categorys. This mutation accepts the data that is common to all categorys (title, content, excerpt, slug, etc), but no custom data (such as the price of a Product CPT). So use it with care, only for those category types that do not require to be provided data for their own custom fields (for those, you will need to use a more specific mutation, for that CPT)', 'category-mutations'),
            'updateCategory' => $this->__('Update a category', 'category-mutations'),
            'updateCategorys' => $this->__('Update categorys', 'category-mutations'),
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
                'input' => $this->getRootCreateGenericCategoryInputObjectTypeResolver(),
            ],
            'createCategorys'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreateGenericCategoryInputObjectTypeResolver()),
            'updateCategory' => [
                'input' => $this->getRootUpdateGenericCategoryInputObjectTypeResolver(),
            ],
            'updateCategorys'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateGenericCategoryInputObjectTypeResolver()),
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
                ? $this->getPayloadableCreateGenericCategoryMutationResolver()
                : $this->getCreateGenericCategoryMutationResolver(),
            'createCategorys' => $usePayloadableCategoryMutations
                ? $this->getPayloadableCreateGenericCategoryBulkOperationMutationResolver()
                : $this->getCreateGenericCategoryBulkOperationMutationResolver(),
            'updateCategory' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryMutationResolver()
                : $this->getUpdateGenericCategoryMutationResolver(),
            'updateCategorys' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryBulkOperationMutationResolver()
                : $this->getUpdateGenericCategoryBulkOperationMutationResolver(),
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
                    => $this->getRootCreateGenericCategoryMutationPayloadObjectTypeResolver(),
                'updateCategory',
                'updateCategorys',
                'updateCategoryMutationPayloadObjects'
                    => $this->getRootUpdateGenericCategoryMutationPayloadObjectTypeResolver(),
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
