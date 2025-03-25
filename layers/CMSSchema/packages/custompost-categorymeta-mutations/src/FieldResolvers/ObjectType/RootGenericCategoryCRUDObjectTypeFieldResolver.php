<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\Module;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\AddGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\AddGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\DeleteGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\DeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableAddGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\UpdateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\UpdateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\RootAddGenericCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\RootDeleteGenericCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\RootUpdateGenericCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
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
    private ?RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver $rootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?AddGenericCategoryTermMetaMutationResolver $addGenericCategoryTermMetaMutationResolver = null;
    private ?AddGenericCategoryTermMetaBulkOperationMutationResolver $addGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?DeleteGenericCategoryTermMetaMutationResolver $deleteGenericCategoryTermMetaMutationResolver = null;
    private ?DeleteGenericCategoryTermMetaBulkOperationMutationResolver $deleteGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?UpdateGenericCategoryTermMetaMutationResolver $updateGenericCategoryTermMetaMutationResolver = null;
    private ?UpdateGenericCategoryTermMetaBulkOperationMutationResolver $updateGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMetaMutationResolver $payloadableDeleteGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver $payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMetaMutationResolver $payloadableUpdateGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver $payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableAddGenericCategoryTermMetaMutationResolver $payloadableAddGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver $payloadableAddGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?RootDeleteGenericCategoryTermMetaInputObjectTypeResolver $rootDeleteGenericCategoryTermMetaInputObjectTypeResolver = null;
    private ?RootUpdateGenericCategoryTermMetaInputObjectTypeResolver $rootUpdateGenericCategoryTermMetaInputObjectTypeResolver = null;
    private ?RootAddGenericCategoryTermMetaInputObjectTypeResolver $rootAddGenericCategoryTermMetaInputObjectTypeResolver = null;
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
    final protected function getRootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver(): RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $rootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getAddGenericCategoryTermMetaMutationResolver(): AddGenericCategoryTermMetaMutationResolver
    {
        if ($this->addGenericCategoryTermMetaMutationResolver === null) {
            /** @var AddGenericCategoryTermMetaMutationResolver */
            $addGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(AddGenericCategoryTermMetaMutationResolver::class);
            $this->addGenericCategoryTermMetaMutationResolver = $addGenericCategoryTermMetaMutationResolver;
        }
        return $this->addGenericCategoryTermMetaMutationResolver;
    }
    final protected function getAddGenericCategoryTermMetaBulkOperationMutationResolver(): AddGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->addGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var AddGenericCategoryTermMetaBulkOperationMutationResolver */
            $addGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(AddGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->addGenericCategoryTermMetaBulkOperationMutationResolver = $addGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->addGenericCategoryTermMetaBulkOperationMutationResolver;
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
    final protected function getPayloadableAddGenericCategoryTermMetaMutationResolver(): PayloadableAddGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableAddGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableAddGenericCategoryTermMetaMutationResolver */
            $payloadableAddGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableAddGenericCategoryTermMetaMutationResolver = $payloadableAddGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableAddGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver(): PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableAddGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver */
            $payloadableAddGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableAddGenericCategoryTermMetaBulkOperationMutationResolver = $payloadableAddGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableAddGenericCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getRootDeleteGenericCategoryTermMetaInputObjectTypeResolver(): RootDeleteGenericCategoryTermMetaInputObjectTypeResolver
    {
        if ($this->rootDeleteGenericCategoryTermMetaInputObjectTypeResolver === null) {
            /** @var RootDeleteGenericCategoryTermMetaInputObjectTypeResolver */
            $rootDeleteGenericCategoryTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMetaInputObjectTypeResolver::class);
            $this->rootDeleteGenericCategoryTermMetaInputObjectTypeResolver = $rootDeleteGenericCategoryTermMetaInputObjectTypeResolver;
        }
        return $this->rootDeleteGenericCategoryTermMetaInputObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryTermMetaInputObjectTypeResolver(): RootUpdateGenericCategoryTermMetaInputObjectTypeResolver
    {
        if ($this->rootUpdateGenericCategoryTermMetaInputObjectTypeResolver === null) {
            /** @var RootUpdateGenericCategoryTermMetaInputObjectTypeResolver */
            $rootUpdateGenericCategoryTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMetaInputObjectTypeResolver::class);
            $this->rootUpdateGenericCategoryTermMetaInputObjectTypeResolver = $rootUpdateGenericCategoryTermMetaInputObjectTypeResolver;
        }
        return $this->rootUpdateGenericCategoryTermMetaInputObjectTypeResolver;
    }
    final protected function getRootAddGenericCategoryTermMetaInputObjectTypeResolver(): RootAddGenericCategoryTermMetaInputObjectTypeResolver
    {
        if ($this->rootAddGenericCategoryTermMetaInputObjectTypeResolver === null) {
            /** @var RootAddGenericCategoryTermMetaInputObjectTypeResolver */
            $rootAddGenericCategoryTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootAddGenericCategoryTermMetaInputObjectTypeResolver::class);
            $this->rootAddGenericCategoryTermMetaInputObjectTypeResolver = $rootAddGenericCategoryTermMetaInputObjectTypeResolver;
        }
        return $this->rootAddGenericCategoryTermMetaInputObjectTypeResolver;
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
                'addCategoryMeta',
                'addCategoryMetas',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updateCategoryMeta',
                'updateCategoryMetas',
                'deleteCategoryMeta',
                'deleteCategoryMetas',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMutations ? [
                'addCategoryMetaMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMutations && !$disableRedundantRootTypeMutationFields ? [
                'updateCategoryMetaMutationPayloadObjects',
                'deleteCategoryMetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addCategoryMeta' => $this->__('Create a category', 'category-mutations'),
            'addCategoryMetas' => $this->__('Create categories', 'category-mutations'),
            'updateCategoryMeta' => $this->__('Update a category', 'category-mutations'),
            'updateCategoryMetas' => $this->__('Update categories', 'category-mutations'),
            'deleteCategoryMeta' => $this->__('Delete a category', 'category-mutations'),
            'deleteCategoryMetas' => $this->__('Delete categories', 'category-mutations'),
            'addCategoryMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCategoryMeta` mutation', 'category-mutations'),
            'updateCategoryMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCategoryMeta` mutation', 'category-mutations'),
            'deleteCategoryMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteCategoryMeta` mutation', 'category-mutations'),
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
                'addCategoryMeta',
                'updateCategoryMeta',
                'deleteCategoryMeta'
                    => SchemaTypeModifiers::NONE,
                'addCategoryMetas',
                'updateCategoryMetas',
                'deleteCategoryMetas'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'addCategoryMetaMutationPayloadObjects',
            'updateCategoryMetaMutationPayloadObjects',
            'deleteCategoryMetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'addCategoryMeta',
            'updateCategoryMeta',
            'deleteCategoryMeta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'addCategoryMetas',
            'updateCategoryMetas',
            'deleteCategoryMetas'
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
            'addCategoryMeta' => [
                'input' => $this->getRootAddGenericCategoryTermMetaInputObjectTypeResolver(),
            ],
            'addCategoryMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddGenericCategoryTermMetaInputObjectTypeResolver()),
            'updateCategoryMeta' => [
                'input' => $this->getRootUpdateGenericCategoryTermMetaInputObjectTypeResolver(),
            ],
            'updateCategoryMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateGenericCategoryTermMetaInputObjectTypeResolver()),
            'deleteCategoryMeta' => [
                'input' => $this->getRootDeleteGenericCategoryTermMetaInputObjectTypeResolver(),
            ],
            'deleteCategoryMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteGenericCategoryTermMetaInputObjectTypeResolver()),
            'addCategoryMetaMutationPayloadObjects',
            'updateCategoryMetaMutationPayloadObjects',
            'deleteCategoryMetaMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'addCategoryMetaMutationPayloadObjects',
            'updateCategoryMetaMutationPayloadObjects',
            'deleteCategoryMetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'addCategoryMetas',
            'updateCategoryMetas',
            'deleteCategoryMetas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['addCategoryMeta' => 'input'],
            ['updateCategoryMeta' => 'input'],
            ['deleteCategoryMeta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'addCategoryMetas',
            'updateCategoryMetas',
            'deleteCategoryMetas',
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
            'addCategoryMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableAddGenericCategoryTermMetaMutationResolver()
                : $this->getAddGenericCategoryTermMetaMutationResolver(),
            'addCategoryMetas' => $usePayloadableCategoryMutations
                ? $this->getPayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver()
                : $this->getAddGenericCategoryTermMetaBulkOperationMutationResolver(),
            'updateCategoryMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMetaMutationResolver()
                : $this->getUpdateGenericCategoryTermMetaMutationResolver(),
            'updateCategoryMetas' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver()
                : $this->getUpdateGenericCategoryTermMetaBulkOperationMutationResolver(),
            'deleteCategoryMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteGenericCategoryTermMetaMutationResolver()
                : $this->getDeleteGenericCategoryTermMetaMutationResolver(),
            'deleteCategoryMetas' => $usePayloadableCategoryMutations
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
                'addCategoryMeta',
                'addCategoryMetas',
                'addCategoryMetaMutationPayloadObjects'
                    => $this->getRootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver(),
                'updateCategoryMeta',
                'updateCategoryMetas',
                'updateCategoryMetaMutationPayloadObjects'
                    => $this->getRootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver(),
                'deleteCategoryMeta',
                'deleteCategoryMetas',
                'deleteCategoryMetaMutationPayloadObjects'
                    => $this->getRootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addCategoryMeta',
            'addCategoryMetas',
            'updateCategoryMeta',
            'updateCategoryMetas'
                => $this->getGenericCategoryObjectTypeResolver(),
            'deleteCategoryMeta',
            'deleteCategoryMetas'
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
            case 'addCategoryMeta':
            case 'addCategoryMetas':
            case 'updateCategoryMeta':
            case 'updateCategoryMetas':
            case 'deleteCategoryMeta':
            case 'deleteCategoryMetas':
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
            case 'addCategoryMetaMutationPayloadObjects':
            case 'updateCategoryMetaMutationPayloadObjects':
            case 'deleteCategoryMetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
