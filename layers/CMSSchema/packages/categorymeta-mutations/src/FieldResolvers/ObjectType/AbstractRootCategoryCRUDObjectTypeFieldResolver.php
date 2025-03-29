<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootAddCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootDeleteCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootSetCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootUpdateCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\Module;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\AddCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\AddCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\DeleteCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\DeleteCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableAddCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableAddCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableDeleteCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableDeleteCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableSetCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableSetCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableUpdateCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableUpdateCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\SetCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\SetCategoryTermMetaMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\UpdateCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\UpdateCategoryTermMetaMutationResolver;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\BulkOperationDecoratorObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

abstract class AbstractRootCategoryCRUDObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?AddCategoryTermMetaMutationResolver $addCategoryTermMetaMutationResolver = null;
    private ?AddCategoryTermMetaBulkOperationMutationResolver $addCategoryTermMetaBulkOperationMutationResolver = null;
    private ?DeleteCategoryTermMetaMutationResolver $deleteCategoryTermMetaMutationResolver = null;
    private ?DeleteCategoryTermMetaBulkOperationMutationResolver $deleteCategoryTermMetaBulkOperationMutationResolver = null;
    private ?SetCategoryTermMetaMutationResolver $setCategoryTermMetaMutationResolver = null;
    private ?SetCategoryTermMetaBulkOperationMutationResolver $setCategoryTermMetaBulkOperationMutationResolver = null;
    private ?UpdateCategoryTermMetaMutationResolver $updateCategoryTermMetaMutationResolver = null;
    private ?UpdateCategoryTermMetaBulkOperationMutationResolver $updateCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteCategoryTermMetaMutationResolver $payloadableDeleteCategoryTermMetaMutationResolver = null;
    private ?PayloadableDeleteCategoryTermMetaBulkOperationMutationResolver $payloadableDeleteCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableSetCategoryTermMetaMutationResolver $payloadableSetCategoryTermMetaMutationResolver = null;
    private ?PayloadableSetCategoryTermMetaBulkOperationMutationResolver $payloadableSetCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateCategoryTermMetaMutationResolver $payloadableUpdateCategoryTermMetaMutationResolver = null;
    private ?PayloadableUpdateCategoryTermMetaBulkOperationMutationResolver $payloadableUpdateCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableAddCategoryTermMetaMutationResolver $payloadableAddCategoryTermMetaMutationResolver = null;
    private ?PayloadableAddCategoryTermMetaBulkOperationMutationResolver $payloadableAddCategoryTermMetaBulkOperationMutationResolver = null;
    private ?RootDeleteCategoryTermMetaInputObjectTypeResolver $rootDeleteCategoryTermMetaInputObjectTypeResolver = null;
    private ?RootSetCategoryTermMetaInputObjectTypeResolver $rootSetCategoryTermMetaInputObjectTypeResolver = null;
    private ?RootUpdateCategoryTermMetaInputObjectTypeResolver $rootUpdateCategoryTermMetaInputObjectTypeResolver = null;
    private ?RootAddCategoryTermMetaInputObjectTypeResolver $rootAddCategoryTermMetaInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getAddCategoryTermMetaMutationResolver(): AddCategoryTermMetaMutationResolver
    {
        if ($this->addCategoryTermMetaMutationResolver === null) {
            /** @var AddCategoryTermMetaMutationResolver */
            $addCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(AddCategoryTermMetaMutationResolver::class);
            $this->addCategoryTermMetaMutationResolver = $addCategoryTermMetaMutationResolver;
        }
        return $this->addCategoryTermMetaMutationResolver;
    }
    final protected function getAddCategoryTermMetaBulkOperationMutationResolver(): AddCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->addCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var AddCategoryTermMetaBulkOperationMutationResolver */
            $addCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(AddCategoryTermMetaBulkOperationMutationResolver::class);
            $this->addCategoryTermMetaBulkOperationMutationResolver = $addCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->addCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getDeleteCategoryTermMetaMutationResolver(): DeleteCategoryTermMetaMutationResolver
    {
        if ($this->deleteCategoryTermMetaMutationResolver === null) {
            /** @var DeleteCategoryTermMetaMutationResolver */
            $deleteCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(DeleteCategoryTermMetaMutationResolver::class);
            $this->deleteCategoryTermMetaMutationResolver = $deleteCategoryTermMetaMutationResolver;
        }
        return $this->deleteCategoryTermMetaMutationResolver;
    }
    final protected function getDeleteCategoryTermMetaBulkOperationMutationResolver(): DeleteCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->deleteCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var DeleteCategoryTermMetaBulkOperationMutationResolver */
            $deleteCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteCategoryTermMetaBulkOperationMutationResolver::class);
            $this->deleteCategoryTermMetaBulkOperationMutationResolver = $deleteCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->deleteCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getSetCategoryTermMetaMutationResolver(): SetCategoryTermMetaMutationResolver
    {
        if ($this->setCategoryTermMetaMutationResolver === null) {
            /** @var SetCategoryTermMetaMutationResolver */
            $setCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(SetCategoryTermMetaMutationResolver::class);
            $this->setCategoryTermMetaMutationResolver = $setCategoryTermMetaMutationResolver;
        }
        return $this->setCategoryTermMetaMutationResolver;
    }
    final protected function getSetCategoryTermMetaBulkOperationMutationResolver(): SetCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->setCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var SetCategoryTermMetaBulkOperationMutationResolver */
            $setCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(SetCategoryTermMetaBulkOperationMutationResolver::class);
            $this->setCategoryTermMetaBulkOperationMutationResolver = $setCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->setCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getUpdateCategoryTermMetaMutationResolver(): UpdateCategoryTermMetaMutationResolver
    {
        if ($this->updateCategoryTermMetaMutationResolver === null) {
            /** @var UpdateCategoryTermMetaMutationResolver */
            $updateCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateCategoryTermMetaMutationResolver::class);
            $this->updateCategoryTermMetaMutationResolver = $updateCategoryTermMetaMutationResolver;
        }
        return $this->updateCategoryTermMetaMutationResolver;
    }
    final protected function getUpdateCategoryTermMetaBulkOperationMutationResolver(): UpdateCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->updateCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var UpdateCategoryTermMetaBulkOperationMutationResolver */
            $updateCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateCategoryTermMetaBulkOperationMutationResolver::class);
            $this->updateCategoryTermMetaBulkOperationMutationResolver = $updateCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->updateCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteCategoryTermMetaMutationResolver(): PayloadableDeleteCategoryTermMetaMutationResolver
    {
        if ($this->payloadableDeleteCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableDeleteCategoryTermMetaMutationResolver */
            $payloadableDeleteCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCategoryTermMetaMutationResolver::class);
            $this->payloadableDeleteCategoryTermMetaMutationResolver = $payloadableDeleteCategoryTermMetaMutationResolver;
        }
        return $this->payloadableDeleteCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableDeleteCategoryTermMetaBulkOperationMutationResolver(): PayloadableDeleteCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteCategoryTermMetaBulkOperationMutationResolver */
            $payloadableDeleteCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableDeleteCategoryTermMetaBulkOperationMutationResolver = $payloadableDeleteCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableSetCategoryTermMetaMutationResolver(): PayloadableSetCategoryTermMetaMutationResolver
    {
        if ($this->payloadableSetCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableSetCategoryTermMetaMutationResolver */
            $payloadableSetCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetCategoryTermMetaMutationResolver::class);
            $this->payloadableSetCategoryTermMetaMutationResolver = $payloadableSetCategoryTermMetaMutationResolver;
        }
        return $this->payloadableSetCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableSetCategoryTermMetaBulkOperationMutationResolver(): PayloadableSetCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableSetCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableSetCategoryTermMetaBulkOperationMutationResolver */
            $payloadableSetCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableSetCategoryTermMetaBulkOperationMutationResolver = $payloadableSetCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableSetCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateCategoryTermMetaMutationResolver(): PayloadableUpdateCategoryTermMetaMutationResolver
    {
        if ($this->payloadableUpdateCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateCategoryTermMetaMutationResolver */
            $payloadableUpdateCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCategoryTermMetaMutationResolver::class);
            $this->payloadableUpdateCategoryTermMetaMutationResolver = $payloadableUpdateCategoryTermMetaMutationResolver;
        }
        return $this->payloadableUpdateCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableUpdateCategoryTermMetaBulkOperationMutationResolver(): PayloadableUpdateCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateCategoryTermMetaBulkOperationMutationResolver */
            $payloadableUpdateCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableUpdateCategoryTermMetaBulkOperationMutationResolver = $payloadableUpdateCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableAddCategoryTermMetaMutationResolver(): PayloadableAddCategoryTermMetaMutationResolver
    {
        if ($this->payloadableAddCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableAddCategoryTermMetaMutationResolver */
            $payloadableAddCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddCategoryTermMetaMutationResolver::class);
            $this->payloadableAddCategoryTermMetaMutationResolver = $payloadableAddCategoryTermMetaMutationResolver;
        }
        return $this->payloadableAddCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableAddCategoryTermMetaBulkOperationMutationResolver(): PayloadableAddCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableAddCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableAddCategoryTermMetaBulkOperationMutationResolver */
            $payloadableAddCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableAddCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableAddCategoryTermMetaBulkOperationMutationResolver = $payloadableAddCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableAddCategoryTermMetaBulkOperationMutationResolver;
    }
    final protected function getRootDeleteCategoryTermMetaInputObjectTypeResolver(): RootDeleteCategoryTermMetaInputObjectTypeResolver
    {
        if ($this->rootDeleteCategoryTermMetaInputObjectTypeResolver === null) {
            /** @var RootDeleteCategoryTermMetaInputObjectTypeResolver */
            $rootDeleteCategoryTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteCategoryTermMetaInputObjectTypeResolver::class);
            $this->rootDeleteCategoryTermMetaInputObjectTypeResolver = $rootDeleteCategoryTermMetaInputObjectTypeResolver;
        }
        return $this->rootDeleteCategoryTermMetaInputObjectTypeResolver;
    }
    final protected function getRootSetCategoryTermMetaInputObjectTypeResolver(): RootSetCategoryTermMetaInputObjectTypeResolver
    {
        if ($this->rootSetCategoryTermMetaInputObjectTypeResolver === null) {
            /** @var RootSetCategoryTermMetaInputObjectTypeResolver */
            $rootSetCategoryTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetCategoryTermMetaInputObjectTypeResolver::class);
            $this->rootSetCategoryTermMetaInputObjectTypeResolver = $rootSetCategoryTermMetaInputObjectTypeResolver;
        }
        return $this->rootSetCategoryTermMetaInputObjectTypeResolver;
    }
    final protected function getRootUpdateCategoryTermMetaInputObjectTypeResolver(): RootUpdateCategoryTermMetaInputObjectTypeResolver
    {
        if ($this->rootUpdateCategoryTermMetaInputObjectTypeResolver === null) {
            /** @var RootUpdateCategoryTermMetaInputObjectTypeResolver */
            $rootUpdateCategoryTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateCategoryTermMetaInputObjectTypeResolver::class);
            $this->rootUpdateCategoryTermMetaInputObjectTypeResolver = $rootUpdateCategoryTermMetaInputObjectTypeResolver;
        }
        return $this->rootUpdateCategoryTermMetaInputObjectTypeResolver;
    }
    final protected function getRootAddCategoryTermMetaInputObjectTypeResolver(): RootAddCategoryTermMetaInputObjectTypeResolver
    {
        if ($this->rootAddCategoryTermMetaInputObjectTypeResolver === null) {
            /** @var RootAddCategoryTermMetaInputObjectTypeResolver */
            $rootAddCategoryTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootAddCategoryTermMetaInputObjectTypeResolver::class);
            $this->rootAddCategoryTermMetaInputObjectTypeResolver = $rootAddCategoryTermMetaInputObjectTypeResolver;
        }
        return $this->rootAddCategoryTermMetaInputObjectTypeResolver;
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

    abstract protected function getCategoryEntityName(): string;

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        $categoryEntityName = $this->getCategoryEntityName();
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        $disableRedundantRootTypeMutationFields = $engineModuleConfiguration->disableRedundantRootTypeMutationFields();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableCategoryMetaMutations = $moduleConfiguration->addFieldsToQueryPayloadableCategoryMetaMutations();
        return array_merge(
            !$disableRedundantRootTypeMutationFields ? [
                'add' . $categoryEntityName . 'Meta',
                'add' . $categoryEntityName . 'Metas',
                'update' . $categoryEntityName . 'Meta',
                'update' . $categoryEntityName . 'Metas',
                'delete' . $categoryEntityName . 'Meta',
                'delete' . $categoryEntityName . 'Metas',
                'set' . $categoryEntityName . 'Meta',
                'set' . $categoryEntityName . 'Metas',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMetaMutations && !$disableRedundantRootTypeMutationFields ? [
                'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
                'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
                'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
                'set' . $categoryEntityName . 'MetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $categoryEntityName = $this->getCategoryEntityName();
        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta' => $this->__('Add meta to category', 'category-mutations'),
            'add' . $categoryEntityName . 'Metas' => $this->__('Add meta to categories', 'category-mutations'),
            'update' . $categoryEntityName . 'Meta' => $this->__('Update meta from category', 'category-mutations'),
            'update' . $categoryEntityName . 'Metas' => $this->__('Update meta from categories', 'category-mutations'),
            'delete' . $categoryEntityName . 'Meta' => $this->__('Delete meta from category', 'category-mutations'),
            'delete' . $categoryEntityName . 'Metas' => $this->__('Delete meta from categories', 'category-mutations'),
            'set' . $categoryEntityName . 'Meta' => $this->__('Set meta on category', 'category-mutations'),
            'set' . $categoryEntityName . 'Metas' => $this->__('Set meta on categories', 'category-mutations'),
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCategoryMeta` mutation', 'category-mutations'),
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCategoryMeta` mutation', 'category-mutations'),
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteCategoryMeta` mutation', 'category-mutations'),
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `setCategoryMeta` mutation', 'category-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        $categoryEntityName = $this->getCategoryEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if (!$usePayloadableCategoryMetaMutations) {
            return match ($fieldName) {
                'add' . $categoryEntityName . 'Meta',
                'update' . $categoryEntityName . 'Meta',
                'delete' . $categoryEntityName . 'Meta',
                'set' . $categoryEntityName . 'Meta'
                    => SchemaTypeModifiers::NONE,
                'add' . $categoryEntityName . 'Metas',
                'update' . $categoryEntityName . 'Metas',
                'delete' . $categoryEntityName . 'Metas',
                'set' . $categoryEntityName . 'Metas'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta',
            'update' . $categoryEntityName . 'Meta',
            'delete' . $categoryEntityName . 'Meta',
            'set' . $categoryEntityName . 'Meta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'add' . $categoryEntityName . 'Metas',
            'update' . $categoryEntityName . 'Metas',
            'delete' . $categoryEntityName . 'Metas',
            'set' . $categoryEntityName . 'Metas'
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
        $categoryEntityName = $this->getCategoryEntityName();
        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootAddCategoryTermMetaInputObjectTypeResolver(),
            ],
            'add' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddCategoryTermMetaInputObjectTypeResolver()),
            'update' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootUpdateCategoryTermMetaInputObjectTypeResolver(),
            ],
            'update' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateCategoryTermMetaInputObjectTypeResolver()),
            'delete' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootDeleteCategoryTermMetaInputObjectTypeResolver(),
            ],
            'delete' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteCategoryTermMetaInputObjectTypeResolver()),
            'set' . $categoryEntityName . 'Meta' => [
                'input' => $this->getRootSetCategoryTermMetaInputObjectTypeResolver(),
            ],
            'set' . $categoryEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootSetCategoryTermMetaInputObjectTypeResolver()),
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $categoryEntityName = $this->getCategoryEntityName();
        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'update' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'delete' . $categoryEntityName . 'MetaMutationPayloadObjects',
            'set' . $categoryEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'Metas',
            'update' . $categoryEntityName . 'Metas',
            'delete' . $categoryEntityName . 'Metas',
            'set' . $categoryEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['add' . $categoryEntityName . 'Meta' => 'input'],
            ['update' . $categoryEntityName . 'Meta' => 'input'],
            ['delete' . $categoryEntityName . 'Meta' => 'input'],
            ['set' . $categoryEntityName . 'Meta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $categoryEntityName = $this->getCategoryEntityName();
        if (
            in_array($fieldName, [
            'add' . $categoryEntityName . 'Metas',
            'update' . $categoryEntityName . 'Metas',
            'delete' . $categoryEntityName . 'Metas',
            'set' . $categoryEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        $categoryEntityName = $this->getCategoryEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        return match ($fieldName) {
            'add' . $categoryEntityName . 'Meta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableAddCategoryTermMetaMutationResolver()
                : $this->getAddCategoryTermMetaMutationResolver(),
            'add' . $categoryEntityName . 'Metas' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableAddCategoryTermMetaBulkOperationMutationResolver()
                : $this->getAddCategoryTermMetaBulkOperationMutationResolver(),
            'update' . $categoryEntityName . 'Meta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableUpdateCategoryTermMetaMutationResolver()
                : $this->getUpdateCategoryTermMetaMutationResolver(),
            'update' . $categoryEntityName . 'Metas' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableUpdateCategoryTermMetaBulkOperationMutationResolver()
                : $this->getUpdateCategoryTermMetaBulkOperationMutationResolver(),
            'delete' . $categoryEntityName . 'Meta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableDeleteCategoryTermMetaMutationResolver()
                : $this->getDeleteCategoryTermMetaMutationResolver(),
            'delete' . $categoryEntityName . 'Metas' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableDeleteCategoryTermMetaBulkOperationMutationResolver()
                : $this->getDeleteCategoryTermMetaBulkOperationMutationResolver(),
            'set' . $categoryEntityName . 'Meta' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableSetCategoryTermMetaMutationResolver()
                : $this->getSetCategoryTermMetaMutationResolver(),
            'set' . $categoryEntityName . 'Metas' => $usePayloadableCategoryMetaMutations
                ? $this->getPayloadableSetCategoryTermMetaBulkOperationMutationResolver()
                : $this->getSetCategoryTermMetaBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
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
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if ($usePayloadableCategoryMetaMutations) {
            return $validationCheckpoints;
        }

        $categoryEntityName = $this->getCategoryEntityName();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'add' . $categoryEntityName . 'Meta':
            case 'add' . $categoryEntityName . 'Metas':
            case 'update' . $categoryEntityName . 'Meta':
            case 'update' . $categoryEntityName . 'Metas':
            case 'delete' . $categoryEntityName . 'Meta':
            case 'delete' . $categoryEntityName . 'Metas':
            case 'set' . $categoryEntityName . 'Meta':
            case 'set' . $categoryEntityName . 'Metas':
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
        $categoryEntityName = $this->getCategoryEntityName();
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'add' . $categoryEntityName . 'MetaMutationPayloadObjects':
            case 'update' . $categoryEntityName . 'MetaMutationPayloadObjects':
            case 'delete' . $categoryEntityName . 'MetaMutationPayloadObjects':
            case 'set' . $categoryEntityName . 'MetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
