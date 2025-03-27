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
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\AddGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\AddGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\DeleteGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\DeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableAddGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableSetGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\SetGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\SetGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\UpdateGenericCategoryTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\UpdateGenericCategoryTermMetaMutationResolver;
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
    private ?AddGenericCategoryTermMetaMutationResolver $addGenericCategoryTermMetaMutationResolver = null;
    private ?AddGenericCategoryTermMetaBulkOperationMutationResolver $addGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?DeleteGenericCategoryTermMetaMutationResolver $deleteGenericCategoryTermMetaMutationResolver = null;
    private ?DeleteGenericCategoryTermMetaBulkOperationMutationResolver $deleteGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?SetGenericCategoryTermMetaMutationResolver $setGenericCategoryTermMetaMutationResolver = null;
    private ?SetGenericCategoryTermMetaBulkOperationMutationResolver $setGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?UpdateGenericCategoryTermMetaMutationResolver $updateGenericCategoryTermMetaMutationResolver = null;
    private ?UpdateGenericCategoryTermMetaBulkOperationMutationResolver $updateGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMetaMutationResolver $payloadableDeleteGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver $payloadableDeleteGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableSetGenericCategoryTermMetaMutationResolver $payloadableSetGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver $payloadableSetGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMetaMutationResolver $payloadableUpdateGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver $payloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableAddGenericCategoryTermMetaMutationResolver $payloadableAddGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver $payloadableAddGenericCategoryTermMetaBulkOperationMutationResolver = null;
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
    final protected function getSetGenericCategoryTermMetaMutationResolver(): SetGenericCategoryTermMetaMutationResolver
    {
        if ($this->setGenericCategoryTermMetaMutationResolver === null) {
            /** @var SetGenericCategoryTermMetaMutationResolver */
            $setGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(SetGenericCategoryTermMetaMutationResolver::class);
            $this->setGenericCategoryTermMetaMutationResolver = $setGenericCategoryTermMetaMutationResolver;
        }
        return $this->setGenericCategoryTermMetaMutationResolver;
    }
    final protected function getSetGenericCategoryTermMetaBulkOperationMutationResolver(): SetGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->setGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var SetGenericCategoryTermMetaBulkOperationMutationResolver */
            $setGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(SetGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->setGenericCategoryTermMetaBulkOperationMutationResolver = $setGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->setGenericCategoryTermMetaBulkOperationMutationResolver;
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
    final protected function getPayloadableSetGenericCategoryTermMetaMutationResolver(): PayloadableSetGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableSetGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableSetGenericCategoryTermMetaMutationResolver */
            $payloadableSetGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableSetGenericCategoryTermMetaMutationResolver = $payloadableSetGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableSetGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver(): PayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableSetGenericCategoryTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver */
            $payloadableSetGenericCategoryTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver::class);
            $this->payloadableSetGenericCategoryTermMetaBulkOperationMutationResolver = $payloadableSetGenericCategoryTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableSetGenericCategoryTermMetaBulkOperationMutationResolver;
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
            'setCategoryMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableSetGenericCategoryTermMetaMutationResolver()
                : $this->getSetGenericCategoryTermMetaMutationResolver(),
            'setCategoryMetas' => $usePayloadableCategoryMutations
                ? $this->getPayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver()
                : $this->getSetGenericCategoryTermMetaBulkOperationMutationResolver(),
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
