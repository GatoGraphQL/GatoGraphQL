<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\Module;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\CreateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\CreateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\DeleteGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\DeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableCreateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\UpdateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\UpdateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\RootCreateGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\RootDeleteGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\RootUpdateGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
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
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

class RootGenericCategoryCRUDObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?RootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver $rootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver $rootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver $rootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?CreateGenericCategoryTermMetaMutationResolver $createGenericCategoryTermMetaMutationResolver = null;
    private ?CreateGenericCategoryTermMetaBulkOperationMutationResolver $createGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?DeleteGenericCategoryTermMetaMutationResolver $deleteGenericCategoryTermMetaMutationResolver = null;
    private ?DeleteGenericCategoryTermMetaBulkOperationMutationResolver $deleteGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?UpdateGenericCategoryTermMetaMutationResolver $updateGenericCategoryTermMetaMutationResolver = null;
    private ?UpdateGenericCategoryTermMetaBulkOperationMutationResolver $updateGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMetaMutationResolver $payloadableDeleteGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver $payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMetaMutationResolver $payloadableUpdateGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver $payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableCreateGenericCategoryTermMetaMutationResolver $payloadableCreateGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver $payloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?RootDeleteGenericCategoryTermInputObjectTypeResolver $rootDeleteGenericCategoryTermInputObjectTypeResolver = null;
    private ?RootUpdateGenericCategoryTermInputObjectTypeResolver $rootUpdateGenericCategoryTermInputObjectTypeResolver = null;
    private ?RootCreateGenericCategoryTermInputObjectTypeResolver $rootCreateGenericCategoryTermInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final protected function getRootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver(): RootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $rootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver(): RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $rootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver(): RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $rootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getCreateGenericCategoryTermMetaMutationResolver(): CreateGenericCategoryTermMetaMutationResolver
    {
        if ($this->createGenericCategoryTermMetaMutationResolver === null) {
            /** @var CreateGenericCategoryTermMetaMutationResolver */
            $createGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryTermMetaMutationResolver::class);
            $this->createGenericCategoryTermMetaMutationResolver = $createGenericCategoryTermMetaMutationResolver;
        }
        return $this->createGenericCategoryTermMetaMutationResolver;
    }
    final protected function getCreateGenericCategoryTermMetaBulkOperationMutationResolver(): CreateGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->createGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var CreateGenericCategoryTermMetaBulkOperationMutationResolver */
            $createGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->createGenericCategoryTermMetaBulkOperationMutationResolver = $createGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->createGenericCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getDeleteGenericCategoryTermMetaMutationResolver(): DeleteGenericCategoryTermMetaMutationResolver
    {
        if ($this->deleteGenericCategoryTermMetaMutationResolver === null) {
            /** @var DeleteGenericCategoryTermMetaMutationResolver */
            $deleteGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(DeleteGenericCategoryTermMetaMutationResolver::class);
            $this->deleteGenericCategoryTermMetaMutationResolver = $deleteGenericCategoryTermMetaMutationResolver;
        }
        return $this->deleteGenericCategoryTermMetaMutationResolver;
    }
    final protected function getDeleteGenericCategoryTermMetaBulkOperationMutationResolver(): DeleteGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->deleteGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var DeleteGenericCategoryTermMetaBulkOperationMutationResolver */
            $deleteGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->deleteGenericCategoryTermMetaBulkOperationMutationResolver = $deleteGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->deleteGenericCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getUpdateGenericCategoryTermMetaMutationResolver(): UpdateGenericCategoryTermMetaMutationResolver
    {
        if ($this->updateGenericCategoryTermMetaMutationResolver === null) {
            /** @var UpdateGenericCategoryTermMetaMutationResolver */
            $updateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryTermMetaMutationResolver::class);
            $this->updateGenericCategoryTermMetaMutationResolver = $updateGenericCategoryTermMetaMutationResolver;
        }
        return $this->updateGenericCategoryTermMetaMutationResolver;
    }
    final protected function getUpdateGenericCategoryTermMetaBulkOperationMutationResolver(): UpdateGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->updateGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var UpdateGenericCategoryTermMetaBulkOperationMutationResolver */
            $updateGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->updateGenericCategoryTermMetaBulkOperationMutationResolver = $updateGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->updateGenericCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCategoryTermMetaMutationResolver(): PayloadableDeleteGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableDeleteGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableDeleteGenericCategoryTermMetaMutationResolver */
            $payloadableDeleteGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableDeleteGenericCategoryTermMetaMutationResolver = $payloadableDeleteGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableDeleteGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver(): PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver */
            $payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver = $payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryTermMetaMutationResolver(): PayloadableUpdateGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermMetaMutationResolver */
            $payloadableUpdateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermMetaMutationResolver = $payloadableUpdateGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver(): PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver */
            $payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver = $payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryTermMetaMutationResolver(): PayloadableCreateGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryTermMetaMutationResolver */
            $payloadableCreateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableCreateGenericCategoryTermMetaMutationResolver = $payloadableCreateGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver(): PayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver */
            $payloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver = $payloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getRootDeleteGenericCategoryTermInputObjectTypeResolver(): RootDeleteGenericCategoryTermInputObjectTypeResolver
    {
        if ($this->rootDeleteGenericCategoryTermInputObjectTypeResolver === null) {
            /** @var RootDeleteGenericCategoryTermInputObjectTypeResolver */
            $rootDeleteGenericCategoryTermInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermInputObjectTypeResolver::class);
            $this->rootDeleteGenericCategoryTermInputObjectTypeResolver = $rootDeleteGenericCategoryTermInputObjectTypeResolver;
        }
        return $this->rootDeleteGenericCategoryTermInputObjectTypeResolver;
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
    final protected function getRootCreateGenericCategoryTermInputObjectTypeResolver(): RootCreateGenericCategoryTermInputObjectTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermInputObjectTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermInputObjectTypeResolver */
            $rootCreateGenericCategoryTermInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermInputObjectTypeResolver::class);
            $this->rootCreateGenericCategoryTermInputObjectTypeResolver = $rootCreateGenericCategoryTermInputObjectTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermInputObjectTypeResolver;
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
                'createCategories',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updateCategory',
                'updateCategories',
                'deleteCategory',
                'deleteCategories',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMutations ? [
                'createCategoryMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMutations && !$disableRedundantRootTypeMutationFields ? [
                'updateCategoryMutationPayloadObjects',
                'deleteCategoryMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createCategory' => $this->__('Create a category', 'category-mutations'),
            'createCategories' => $this->__('Create categories', 'category-mutations'),
            'updateCategory' => $this->__('Update a category', 'category-mutations'),
            'updateCategories' => $this->__('Update categories', 'category-mutations'),
            'deleteCategory' => $this->__('Delete a category', 'category-mutations'),
            'deleteCategories' => $this->__('Delete categories', 'category-mutations'),
            'createCategoryMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createCategory` mutation', 'category-mutations'),
            'updateCategoryMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCategory` mutation', 'category-mutations'),
            'deleteCategoryMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteCategory` mutation', 'category-mutations'),
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
                'updateCategory',
                'deleteCategory'
                    => SchemaTypeModifiers::NONE,
                'createCategories',
                'updateCategories',
                'deleteCategories'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createCategoryMutationPayloadObjects',
            'updateCategoryMutationPayloadObjects',
            'deleteCategoryMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createCategory',
            'updateCategory',
            'deleteCategory'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createCategories',
            'updateCategories',
            'deleteCategories'
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
            'createCategories'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreateGenericCategoryTermInputObjectTypeResolver()),
            'updateCategory' => [
                'input' => $this->getRootUpdateGenericCategoryTermInputObjectTypeResolver(),
            ],
            'updateCategories'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateGenericCategoryTermInputObjectTypeResolver()),
            'deleteCategory' => [
                'input' => $this->getRootDeleteGenericCategoryTermInputObjectTypeResolver(),
            ],
            'deleteCategories'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteGenericCategoryTermInputObjectTypeResolver()),
            'createCategoryMutationPayloadObjects',
            'updateCategoryMutationPayloadObjects',
            'deleteCategoryMutationPayloadObjects'
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
            'deleteCategoryMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createCategories',
            'updateCategories',
            'deleteCategories',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createCategory' => 'input'],
            ['updateCategory' => 'input'],
            ['deleteCategory' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createCategories',
            'updateCategories',
            'deleteCategories',
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
                ? $this->getPayloadableCreateGenericCategoryTermMetaMutationResolver()
                : $this->getCreateGenericCategoryTermMetaMutationResolver(),
            'createCategories' => $usePayloadableCategoryMutations
                ? $this->getPayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver()
                : $this->getCreateGenericCategoryTermMetaBulkOperationMutationResolver(),
            'updateCategory' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMetaMutationResolver()
                : $this->getUpdateGenericCategoryTermMetaMutationResolver(),
            'updateCategories' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver()
                : $this->getUpdateGenericCategoryTermMetaBulkOperationMutationResolver(),
            'deleteCategory' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteGenericCategoryTermMetaMutationResolver()
                : $this->getDeleteGenericCategoryTermMetaMutationResolver(),
            'deleteCategories' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver()
                : $this->getDeleteGenericCategoryTermMetaBulkOperationMutationResolver(),
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
                'createCategories',
                'createCategoryMutationPayloadObjects'
                    => $this->getRootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver(),
                'updateCategory',
                'updateCategories',
                'updateCategoryMutationPayloadObjects'
                    => $this->getRootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver(),
                'deleteCategory',
                'deleteCategories',
                'deleteCategoryMutationPayloadObjects'
                    => $this->getRootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createCategory',
            'createCategories',
            'updateCategory',
            'updateCategories'
                => $this->getGenericCategoryObjectTypeResolver(),
            'deleteCategory',
            'deleteCategories'
                => $this->getBooleanScalarTypeResolver(),
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
            case 'createCategories':
            case 'updateCategory':
            case 'updateCategories':
            case 'deleteCategory':
            case 'deleteCategories':
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
            case 'deleteCategoryMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
