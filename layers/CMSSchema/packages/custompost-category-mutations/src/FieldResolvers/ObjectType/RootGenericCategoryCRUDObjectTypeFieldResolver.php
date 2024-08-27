<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\Module;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\CreateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\CreateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\DeleteGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\DeleteGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableCreateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableCreateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\UpdateGenericCategoryTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\UpdateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\RootCreateGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\RootDeleteGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\RootUpdateGenericCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver;
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
    private ?RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver $rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver $rootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver = null;
    private ?RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver $rootCreateGenericCategoryTermMutationPayloadObjectTypeResolver = null;
    private ?CreateGenericCategoryTermMutationResolver $createGenericCategoryTermMutationResolver = null;
    private ?CreateGenericCategoryTermBulkOperationMutationResolver $createGenericCategoryTermBulkOperationMutationResolver = null;
    private ?DeleteGenericCategoryTermMutationResolver $deleteGenericCategoryTermMutationResolver = null;
    private ?DeleteGenericCategoryTermBulkOperationMutationResolver $deleteGenericCategoryTermBulkOperationMutationResolver = null;
    private ?UpdateGenericCategoryTermMutationResolver $updateGenericCategoryTermMutationResolver = null;
    private ?UpdateGenericCategoryTermBulkOperationMutationResolver $updateGenericCategoryTermBulkOperationMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMutationResolver $payloadableDeleteGenericCategoryTermMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermBulkOperationMutationResolver $payloadableDeleteGenericCategoryTermBulkOperationMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMutationResolver $payloadableUpdateGenericCategoryTermMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver $payloadableUpdateGenericCategoryTermBulkOperationMutationResolver = null;
    private ?PayloadableCreateGenericCategoryTermMutationResolver $payloadableCreateGenericCategoryTermMutationResolver = null;
    private ?PayloadableCreateGenericCategoryTermBulkOperationMutationResolver $payloadableCreateGenericCategoryTermBulkOperationMutationResolver = null;
    private ?RootDeleteGenericCategoryTermInputObjectTypeResolver $rootDeleteGenericCategoryTermInputObjectTypeResolver = null;
    private ?RootUpdateGenericCategoryTermInputObjectTypeResolver $rootUpdateGenericCategoryTermInputObjectTypeResolver = null;
    private ?RootCreateGenericCategoryTermInputObjectTypeResolver $rootCreateGenericCategoryTermInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

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
    final public function setRootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver(RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver $rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver): void
    {
        $this->rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver = $rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver;
    }
    final protected function getRootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver(): RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver */
            $rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver = $rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver;
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
    final public function setDeleteGenericCategoryTermMutationResolver(DeleteGenericCategoryTermMutationResolver $deleteGenericCategoryTermMutationResolver): void
    {
        $this->deleteGenericCategoryTermMutationResolver = $deleteGenericCategoryTermMutationResolver;
    }
    final protected function getDeleteGenericCategoryTermMutationResolver(): DeleteGenericCategoryTermMutationResolver
    {
        if ($this->deleteGenericCategoryTermMutationResolver === null) {
            /** @var DeleteGenericCategoryTermMutationResolver */
            $deleteGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(DeleteGenericCategoryTermMutationResolver::class);
            $this->deleteGenericCategoryTermMutationResolver = $deleteGenericCategoryTermMutationResolver;
        }
        return $this->deleteGenericCategoryTermMutationResolver;
    }
    final public function setDeleteGenericCategoryTermBulkOperationMutationResolver(DeleteGenericCategoryTermBulkOperationMutationResolver $deleteGenericCategoryTermBulkOperationMutationResolver): void
    {
        $this->deleteGenericCategoryTermBulkOperationMutationResolver = $deleteGenericCategoryTermBulkOperationMutationResolver;
    }
    final protected function getDeleteGenericCategoryTermBulkOperationMutationResolver(): DeleteGenericCategoryTermBulkOperationMutationResolver
    {
        if ($this->deleteGenericCategoryTermBulkOperationMutationResolver === null) {
            /** @var DeleteGenericCategoryTermBulkOperationMutationResolver */
            $deleteGenericCategoryTermBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteGenericCategoryTermBulkOperationMutationResolver::class);
            $this->deleteGenericCategoryTermBulkOperationMutationResolver = $deleteGenericCategoryTermBulkOperationMutationResolver;
        }
        return $this->deleteGenericCategoryTermBulkOperationMutationResolver;
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
    final public function setPayloadableDeleteGenericCategoryTermMutationResolver(PayloadableDeleteGenericCategoryTermMutationResolver $payloadableDeleteGenericCategoryTermMutationResolver): void
    {
        $this->payloadableDeleteGenericCategoryTermMutationResolver = $payloadableDeleteGenericCategoryTermMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCategoryTermMutationResolver(): PayloadableDeleteGenericCategoryTermMutationResolver
    {
        if ($this->payloadableDeleteGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableDeleteGenericCategoryTermMutationResolver */
            $payloadableDeleteGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCategoryTermMutationResolver::class);
            $this->payloadableDeleteGenericCategoryTermMutationResolver = $payloadableDeleteGenericCategoryTermMutationResolver;
        }
        return $this->payloadableDeleteGenericCategoryTermMutationResolver;
    }
    final public function setPayloadableDeleteGenericCategoryTermBulkOperationMutationResolver(PayloadableDeleteGenericCategoryTermBulkOperationMutationResolver $payloadableDeleteGenericCategoryTermBulkOperationMutationResolver): void
    {
        $this->payloadableDeleteGenericCategoryTermBulkOperationMutationResolver = $payloadableDeleteGenericCategoryTermBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCategoryTermBulkOperationMutationResolver(): PayloadableDeleteGenericCategoryTermBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteGenericCategoryTermBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteGenericCategoryTermBulkOperationMutationResolver */
            $payloadableDeleteGenericCategoryTermBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCategoryTermBulkOperationMutationResolver::class);
            $this->payloadableDeleteGenericCategoryTermBulkOperationMutationResolver = $payloadableDeleteGenericCategoryTermBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteGenericCategoryTermBulkOperationMutationResolver;
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
    final public function setRootDeleteGenericCategoryTermInputObjectTypeResolver(RootDeleteGenericCategoryTermInputObjectTypeResolver $rootDeleteGenericCategoryTermInputObjectTypeResolver): void
    {
        $this->rootDeleteGenericCategoryTermInputObjectTypeResolver = $rootDeleteGenericCategoryTermInputObjectTypeResolver;
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
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
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
                ? $this->getPayloadableCreateGenericCategoryTermMutationResolver()
                : $this->getCreateGenericCategoryTermMutationResolver(),
            'createCategories' => $usePayloadableCategoryMutations
                ? $this->getPayloadableCreateGenericCategoryTermBulkOperationMutationResolver()
                : $this->getCreateGenericCategoryTermBulkOperationMutationResolver(),
            'updateCategory' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMutationResolver()
                : $this->getUpdateGenericCategoryTermMutationResolver(),
            'updateCategories' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermBulkOperationMutationResolver()
                : $this->getUpdateGenericCategoryTermBulkOperationMutationResolver(),
            'deleteCategory' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteGenericCategoryTermMutationResolver()
                : $this->getDeleteGenericCategoryTermMutationResolver(),
            'deleteCategories' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteGenericCategoryTermBulkOperationMutationResolver()
                : $this->getDeleteGenericCategoryTermBulkOperationMutationResolver(),
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
                    => $this->getRootCreateGenericCategoryTermMutationPayloadObjectTypeResolver(),
                'updateCategory',
                'updateCategories',
                'updateCategoryMutationPayloadObjects'
                    => $this->getRootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver(),
                'deleteCategory',
                'deleteCategories',
                'deleteCategoryMutationPayloadObjects'
                    => $this->getRootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver(),
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
