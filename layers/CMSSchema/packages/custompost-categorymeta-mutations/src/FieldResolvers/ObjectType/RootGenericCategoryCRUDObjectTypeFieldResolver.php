<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootAddCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootDeleteCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootSetCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\RootUpdateCategoryTermMetaInputObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\Module;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration;
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
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
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
    private ?RootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver $rootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver $rootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver $rootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver = null;
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
    final protected function getRootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver(): RootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver */
            $rootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver = $rootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
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
            !$disableRedundantRootTypeMutationFields ? [
                'addCategoryMeta',
                'addCategoryMetas',
                'updateCategoryMeta',
                'updateCategoryMetas',
                'deleteCategoryMeta',
                'deleteCategoryMetas',
                'setCategoryMeta',
                'setCategoryMetas',
            ] : [],
            $addFieldsToQueryPayloadableCategoryMutations && !$disableRedundantRootTypeMutationFields ? [
                'addCategoryMetaMutationPayloadObjects',
                'updateCategoryMetaMutationPayloadObjects',
                'deleteCategoryMetaMutationPayloadObjects',
                'setCategoryMetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addCategoryMeta' => $this->__('Add meta to category', 'category-mutations'),
            'addCategoryMetas' => $this->__('Add meta to categories', 'category-mutations'),
            'updateCategoryMeta' => $this->__('Update meta from category', 'category-mutations'),
            'updateCategoryMetas' => $this->__('Update meta from categories', 'category-mutations'),
            'deleteCategoryMeta' => $this->__('Delete meta from category', 'category-mutations'),
            'deleteCategoryMetas' => $this->__('Delete meta from categories', 'category-mutations'),
            'setCategoryMeta' => $this->__('Set meta on category', 'category-mutations'),
            'setCategoryMetas' => $this->__('Set meta on categories', 'category-mutations'),
            'addCategoryMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCategoryMeta` mutation', 'category-mutations'),
            'updateCategoryMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCategoryMeta` mutation', 'category-mutations'),
            'deleteCategoryMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteCategoryMeta` mutation', 'category-mutations'),
            'setCategoryMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `setCategoryMeta` mutation', 'category-mutations'),
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
                'deleteCategoryMeta',
                'setCategoryMeta'
                    => SchemaTypeModifiers::NONE,
                'addCategoryMetas',
                'updateCategoryMetas',
                'deleteCategoryMetas',
                'setCategoryMetas'
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
            'setCategoryMetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'addCategoryMeta',
            'updateCategoryMeta',
            'deleteCategoryMeta',
            'setCategoryMeta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'addCategoryMetas',
            'updateCategoryMetas',
            'deleteCategoryMetas',
            'setCategoryMetas'
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
                'input' => $this->getRootAddCategoryTermMetaInputObjectTypeResolver(),
            ],
            'addCategoryMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddCategoryTermMetaInputObjectTypeResolver()),
            'updateCategoryMeta' => [
                'input' => $this->getRootUpdateCategoryTermMetaInputObjectTypeResolver(),
            ],
            'updateCategoryMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateCategoryTermMetaInputObjectTypeResolver()),
            'deleteCategoryMeta' => [
                'input' => $this->getRootDeleteCategoryTermMetaInputObjectTypeResolver(),
            ],
            'deleteCategoryMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteCategoryTermMetaInputObjectTypeResolver()),
            'setCategoryMeta' => [
                'input' => $this->getRootSetCategoryTermMetaInputObjectTypeResolver(),
            ],
            'setCategoryMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootSetCategoryTermMetaInputObjectTypeResolver()),
            'addCategoryMetaMutationPayloadObjects',
            'updateCategoryMetaMutationPayloadObjects',
            'deleteCategoryMetaMutationPayloadObjects',
            'setCategoryMetaMutationPayloadObjects'
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
            'setCategoryMetaMutationPayloadObjects',
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
            'setCategoryMetas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['addCategoryMeta' => 'input'],
            ['updateCategoryMeta' => 'input'],
            ['deleteCategoryMeta' => 'input'],
            ['setCategoryMeta' => 'input']
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
            'setCategoryMetas',
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
                ? $this->getPayloadableAddCategoryTermMetaMutationResolver()
                : $this->getAddCategoryTermMetaMutationResolver(),
            'addCategoryMetas' => $usePayloadableCategoryMutations
                ? $this->getPayloadableAddCategoryTermMetaBulkOperationMutationResolver()
                : $this->getAddCategoryTermMetaBulkOperationMutationResolver(),
            'updateCategoryMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateCategoryTermMetaMutationResolver()
                : $this->getUpdateCategoryTermMetaMutationResolver(),
            'updateCategoryMetas' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateCategoryTermMetaBulkOperationMutationResolver()
                : $this->getUpdateCategoryTermMetaBulkOperationMutationResolver(),
            'deleteCategoryMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteCategoryTermMetaMutationResolver()
                : $this->getDeleteCategoryTermMetaMutationResolver(),
            'deleteCategoryMetas' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteCategoryTermMetaBulkOperationMutationResolver()
                : $this->getDeleteCategoryTermMetaBulkOperationMutationResolver(),
            'setCategoryMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableSetCategoryTermMetaMutationResolver()
                : $this->getSetCategoryTermMetaMutationResolver(),
            'setCategoryMetas' => $usePayloadableCategoryMutations
                ? $this->getPayloadableSetCategoryTermMetaBulkOperationMutationResolver()
                : $this->getSetCategoryTermMetaBulkOperationMutationResolver(),
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
                'setCategoryMeta',
                'setCategoryMetas',
                'setCategoryMetaMutationPayloadObjects'
                    => $this->getRootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addCategoryMeta',
            'addCategoryMetas',
            'updateCategoryMeta',
            'updateCategoryMetas',
            'deleteCategoryMeta',
            'deleteCategoryMetas',
            'setCategoryMeta',
            'setCategoryMetas'
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
            case 'addCategoryMeta':
            case 'addCategoryMetas':
            case 'updateCategoryMeta':
            case 'updateCategoryMetas':
            case 'deleteCategoryMeta':
            case 'deleteCategoryMetas':
            case 'setCategoryMeta':
            case 'setCategoryMetas':
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
            case 'setCategoryMetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
