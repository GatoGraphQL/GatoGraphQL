<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\TagMutations\Module;
use PoPCMSSchema\TagMutations\ModuleConfiguration;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\CreateGenericTagTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\CreateGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\DeleteGenericTagTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\DeleteGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableCreateGenericTagTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableCreateGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableDeleteGenericTagTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableDeleteGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableUpdateGenericTagTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableUpdateGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\UpdateGenericTagTermBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\UpdateGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\RootCreateGenericTagTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\RootDeleteGenericTagTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\RootUpdateGenericTagTermInputObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\RootCreateGenericTagTermMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\RootDeleteGenericTagTermMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\RootUpdateGenericTagTermMutationPayloadObjectTypeResolver;
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

class RootGenericTagCRUDObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?RootDeleteGenericTagTermMutationPayloadObjectTypeResolver $rootDeleteGenericTagTermMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericTagTermMutationPayloadObjectTypeResolver $rootUpdateGenericTagTermMutationPayloadObjectTypeResolver = null;
    private ?RootCreateGenericTagTermMutationPayloadObjectTypeResolver $rootCreateGenericTagTermMutationPayloadObjectTypeResolver = null;
    private ?CreateGenericTagTermMutationResolver $createGenericTagTermMutationResolver = null;
    private ?CreateGenericTagTermBulkOperationMutationResolver $createGenericTagTermBulkOperationMutationResolver = null;
    private ?DeleteGenericTagTermMutationResolver $deleteGenericTagTermMutationResolver = null;
    private ?DeleteGenericTagTermBulkOperationMutationResolver $deleteGenericTagTermBulkOperationMutationResolver = null;
    private ?UpdateGenericTagTermMutationResolver $updateGenericTagTermMutationResolver = null;
    private ?UpdateGenericTagTermBulkOperationMutationResolver $updateGenericTagTermBulkOperationMutationResolver = null;
    private ?PayloadableDeleteGenericTagTermMutationResolver $payloadableDeleteGenericTagTermMutationResolver = null;
    private ?PayloadableDeleteGenericTagTermBulkOperationMutationResolver $payloadableDeleteGenericTagTermBulkOperationMutationResolver = null;
    private ?PayloadableUpdateGenericTagTermMutationResolver $payloadableUpdateGenericTagTermMutationResolver = null;
    private ?PayloadableUpdateGenericTagTermBulkOperationMutationResolver $payloadableUpdateGenericTagTermBulkOperationMutationResolver = null;
    private ?PayloadableCreateGenericTagTermMutationResolver $payloadableCreateGenericTagTermMutationResolver = null;
    private ?PayloadableCreateGenericTagTermBulkOperationMutationResolver $payloadableCreateGenericTagTermBulkOperationMutationResolver = null;
    private ?RootDeleteGenericTagTermInputObjectTypeResolver $rootDeleteGenericTagTermInputObjectTypeResolver = null;
    private ?RootUpdateGenericTagTermInputObjectTypeResolver $rootUpdateGenericTagTermInputObjectTypeResolver = null;
    private ?RootCreateGenericTagTermInputObjectTypeResolver $rootCreateGenericTagTermInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setGenericTagObjectTypeResolver(GenericTagObjectTypeResolver $genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }
    final public function setRootDeleteGenericTagTermMutationPayloadObjectTypeResolver(RootDeleteGenericTagTermMutationPayloadObjectTypeResolver $rootDeleteGenericTagTermMutationPayloadObjectTypeResolver): void
    {
        $this->rootDeleteGenericTagTermMutationPayloadObjectTypeResolver = $rootDeleteGenericTagTermMutationPayloadObjectTypeResolver;
    }
    final protected function getRootDeleteGenericTagTermMutationPayloadObjectTypeResolver(): RootDeleteGenericTagTermMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteGenericTagTermMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteGenericTagTermMutationPayloadObjectTypeResolver */
            $rootDeleteGenericTagTermMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericTagTermMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteGenericTagTermMutationPayloadObjectTypeResolver = $rootDeleteGenericTagTermMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteGenericTagTermMutationPayloadObjectTypeResolver;
    }
    final public function setRootUpdateGenericTagTermMutationPayloadObjectTypeResolver(RootUpdateGenericTagTermMutationPayloadObjectTypeResolver $rootUpdateGenericTagTermMutationPayloadObjectTypeResolver): void
    {
        $this->rootUpdateGenericTagTermMutationPayloadObjectTypeResolver = $rootUpdateGenericTagTermMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericTagTermMutationPayloadObjectTypeResolver(): RootUpdateGenericTagTermMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericTagTermMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericTagTermMutationPayloadObjectTypeResolver */
            $rootUpdateGenericTagTermMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericTagTermMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericTagTermMutationPayloadObjectTypeResolver = $rootUpdateGenericTagTermMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericTagTermMutationPayloadObjectTypeResolver;
    }
    final public function setRootCreateGenericTagTermMutationPayloadObjectTypeResolver(RootCreateGenericTagTermMutationPayloadObjectTypeResolver $rootCreateGenericTagTermMutationPayloadObjectTypeResolver): void
    {
        $this->rootCreateGenericTagTermMutationPayloadObjectTypeResolver = $rootCreateGenericTagTermMutationPayloadObjectTypeResolver;
    }
    final protected function getRootCreateGenericTagTermMutationPayloadObjectTypeResolver(): RootCreateGenericTagTermMutationPayloadObjectTypeResolver
    {
        if ($this->rootCreateGenericTagTermMutationPayloadObjectTypeResolver === null) {
            /** @var RootCreateGenericTagTermMutationPayloadObjectTypeResolver */
            $rootCreateGenericTagTermMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericTagTermMutationPayloadObjectTypeResolver::class);
            $this->rootCreateGenericTagTermMutationPayloadObjectTypeResolver = $rootCreateGenericTagTermMutationPayloadObjectTypeResolver;
        }
        return $this->rootCreateGenericTagTermMutationPayloadObjectTypeResolver;
    }
    final public function setCreateGenericTagTermMutationResolver(CreateGenericTagTermMutationResolver $createGenericTagTermMutationResolver): void
    {
        $this->createGenericTagTermMutationResolver = $createGenericTagTermMutationResolver;
    }
    final protected function getCreateGenericTagTermMutationResolver(): CreateGenericTagTermMutationResolver
    {
        if ($this->createGenericTagTermMutationResolver === null) {
            /** @var CreateGenericTagTermMutationResolver */
            $createGenericTagTermMutationResolver = $this->instanceManager->getInstance(CreateGenericTagTermMutationResolver::class);
            $this->createGenericTagTermMutationResolver = $createGenericTagTermMutationResolver;
        }
        return $this->createGenericTagTermMutationResolver;
    }
    final public function setCreateGenericTagTermBulkOperationMutationResolver(CreateGenericTagTermBulkOperationMutationResolver $createGenericTagTermBulkOperationMutationResolver): void
    {
        $this->createGenericTagTermBulkOperationMutationResolver = $createGenericTagTermBulkOperationMutationResolver;
    }
    final protected function getCreateGenericTagTermBulkOperationMutationResolver(): CreateGenericTagTermBulkOperationMutationResolver
    {
        if ($this->createGenericTagTermBulkOperationMutationResolver === null) {
            /** @var CreateGenericTagTermBulkOperationMutationResolver */
            $createGenericTagTermBulkOperationMutationResolver = $this->instanceManager->getInstance(CreateGenericTagTermBulkOperationMutationResolver::class);
            $this->createGenericTagTermBulkOperationMutationResolver = $createGenericTagTermBulkOperationMutationResolver;
        }
        return $this->createGenericTagTermBulkOperationMutationResolver;
    }
    final public function setDeleteGenericTagTermMutationResolver(DeleteGenericTagTermMutationResolver $deleteGenericTagTermMutationResolver): void
    {
        $this->deleteGenericTagTermMutationResolver = $deleteGenericTagTermMutationResolver;
    }
    final protected function getDeleteGenericTagTermMutationResolver(): DeleteGenericTagTermMutationResolver
    {
        if ($this->deleteGenericTagTermMutationResolver === null) {
            /** @var DeleteGenericTagTermMutationResolver */
            $deleteGenericTagTermMutationResolver = $this->instanceManager->getInstance(DeleteGenericTagTermMutationResolver::class);
            $this->deleteGenericTagTermMutationResolver = $deleteGenericTagTermMutationResolver;
        }
        return $this->deleteGenericTagTermMutationResolver;
    }
    final public function setDeleteGenericTagTermBulkOperationMutationResolver(DeleteGenericTagTermBulkOperationMutationResolver $deleteGenericTagTermBulkOperationMutationResolver): void
    {
        $this->deleteGenericTagTermBulkOperationMutationResolver = $deleteGenericTagTermBulkOperationMutationResolver;
    }
    final protected function getDeleteGenericTagTermBulkOperationMutationResolver(): DeleteGenericTagTermBulkOperationMutationResolver
    {
        if ($this->deleteGenericTagTermBulkOperationMutationResolver === null) {
            /** @var DeleteGenericTagTermBulkOperationMutationResolver */
            $deleteGenericTagTermBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteGenericTagTermBulkOperationMutationResolver::class);
            $this->deleteGenericTagTermBulkOperationMutationResolver = $deleteGenericTagTermBulkOperationMutationResolver;
        }
        return $this->deleteGenericTagTermBulkOperationMutationResolver;
    }
    final public function setUpdateGenericTagTermMutationResolver(UpdateGenericTagTermMutationResolver $updateGenericTagTermMutationResolver): void
    {
        $this->updateGenericTagTermMutationResolver = $updateGenericTagTermMutationResolver;
    }
    final protected function getUpdateGenericTagTermMutationResolver(): UpdateGenericTagTermMutationResolver
    {
        if ($this->updateGenericTagTermMutationResolver === null) {
            /** @var UpdateGenericTagTermMutationResolver */
            $updateGenericTagTermMutationResolver = $this->instanceManager->getInstance(UpdateGenericTagTermMutationResolver::class);
            $this->updateGenericTagTermMutationResolver = $updateGenericTagTermMutationResolver;
        }
        return $this->updateGenericTagTermMutationResolver;
    }
    final public function setUpdateGenericTagTermBulkOperationMutationResolver(UpdateGenericTagTermBulkOperationMutationResolver $updateGenericTagTermBulkOperationMutationResolver): void
    {
        $this->updateGenericTagTermBulkOperationMutationResolver = $updateGenericTagTermBulkOperationMutationResolver;
    }
    final protected function getUpdateGenericTagTermBulkOperationMutationResolver(): UpdateGenericTagTermBulkOperationMutationResolver
    {
        if ($this->updateGenericTagTermBulkOperationMutationResolver === null) {
            /** @var UpdateGenericTagTermBulkOperationMutationResolver */
            $updateGenericTagTermBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateGenericTagTermBulkOperationMutationResolver::class);
            $this->updateGenericTagTermBulkOperationMutationResolver = $updateGenericTagTermBulkOperationMutationResolver;
        }
        return $this->updateGenericTagTermBulkOperationMutationResolver;
    }
    final public function setPayloadableDeleteGenericTagTermMutationResolver(PayloadableDeleteGenericTagTermMutationResolver $payloadableDeleteGenericTagTermMutationResolver): void
    {
        $this->payloadableDeleteGenericTagTermMutationResolver = $payloadableDeleteGenericTagTermMutationResolver;
    }
    final protected function getPayloadableDeleteGenericTagTermMutationResolver(): PayloadableDeleteGenericTagTermMutationResolver
    {
        if ($this->payloadableDeleteGenericTagTermMutationResolver === null) {
            /** @var PayloadableDeleteGenericTagTermMutationResolver */
            $payloadableDeleteGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericTagTermMutationResolver::class);
            $this->payloadableDeleteGenericTagTermMutationResolver = $payloadableDeleteGenericTagTermMutationResolver;
        }
        return $this->payloadableDeleteGenericTagTermMutationResolver;
    }
    final public function setPayloadableDeleteGenericTagTermBulkOperationMutationResolver(PayloadableDeleteGenericTagTermBulkOperationMutationResolver $payloadableDeleteGenericTagTermBulkOperationMutationResolver): void
    {
        $this->payloadableDeleteGenericTagTermBulkOperationMutationResolver = $payloadableDeleteGenericTagTermBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteGenericTagTermBulkOperationMutationResolver(): PayloadableDeleteGenericTagTermBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteGenericTagTermBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteGenericTagTermBulkOperationMutationResolver */
            $payloadableDeleteGenericTagTermBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericTagTermBulkOperationMutationResolver::class);
            $this->payloadableDeleteGenericTagTermBulkOperationMutationResolver = $payloadableDeleteGenericTagTermBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteGenericTagTermBulkOperationMutationResolver;
    }
    final public function setPayloadableUpdateGenericTagTermMutationResolver(PayloadableUpdateGenericTagTermMutationResolver $payloadableUpdateGenericTagTermMutationResolver): void
    {
        $this->payloadableUpdateGenericTagTermMutationResolver = $payloadableUpdateGenericTagTermMutationResolver;
    }
    final protected function getPayloadableUpdateGenericTagTermMutationResolver(): PayloadableUpdateGenericTagTermMutationResolver
    {
        if ($this->payloadableUpdateGenericTagTermMutationResolver === null) {
            /** @var PayloadableUpdateGenericTagTermMutationResolver */
            $payloadableUpdateGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericTagTermMutationResolver::class);
            $this->payloadableUpdateGenericTagTermMutationResolver = $payloadableUpdateGenericTagTermMutationResolver;
        }
        return $this->payloadableUpdateGenericTagTermMutationResolver;
    }
    final public function setPayloadableUpdateGenericTagTermBulkOperationMutationResolver(PayloadableUpdateGenericTagTermBulkOperationMutationResolver $payloadableUpdateGenericTagTermBulkOperationMutationResolver): void
    {
        $this->payloadableUpdateGenericTagTermBulkOperationMutationResolver = $payloadableUpdateGenericTagTermBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateGenericTagTermBulkOperationMutationResolver(): PayloadableUpdateGenericTagTermBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateGenericTagTermBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateGenericTagTermBulkOperationMutationResolver */
            $payloadableUpdateGenericTagTermBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericTagTermBulkOperationMutationResolver::class);
            $this->payloadableUpdateGenericTagTermBulkOperationMutationResolver = $payloadableUpdateGenericTagTermBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateGenericTagTermBulkOperationMutationResolver;
    }
    final public function setPayloadableCreateGenericTagTermMutationResolver(PayloadableCreateGenericTagTermMutationResolver $payloadableCreateGenericTagTermMutationResolver): void
    {
        $this->payloadableCreateGenericTagTermMutationResolver = $payloadableCreateGenericTagTermMutationResolver;
    }
    final protected function getPayloadableCreateGenericTagTermMutationResolver(): PayloadableCreateGenericTagTermMutationResolver
    {
        if ($this->payloadableCreateGenericTagTermMutationResolver === null) {
            /** @var PayloadableCreateGenericTagTermMutationResolver */
            $payloadableCreateGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericTagTermMutationResolver::class);
            $this->payloadableCreateGenericTagTermMutationResolver = $payloadableCreateGenericTagTermMutationResolver;
        }
        return $this->payloadableCreateGenericTagTermMutationResolver;
    }
    final public function setPayloadableCreateGenericTagTermBulkOperationMutationResolver(PayloadableCreateGenericTagTermBulkOperationMutationResolver $payloadableCreateGenericTagTermBulkOperationMutationResolver): void
    {
        $this->payloadableCreateGenericTagTermBulkOperationMutationResolver = $payloadableCreateGenericTagTermBulkOperationMutationResolver;
    }
    final protected function getPayloadableCreateGenericTagTermBulkOperationMutationResolver(): PayloadableCreateGenericTagTermBulkOperationMutationResolver
    {
        if ($this->payloadableCreateGenericTagTermBulkOperationMutationResolver === null) {
            /** @var PayloadableCreateGenericTagTermBulkOperationMutationResolver */
            $payloadableCreateGenericTagTermBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericTagTermBulkOperationMutationResolver::class);
            $this->payloadableCreateGenericTagTermBulkOperationMutationResolver = $payloadableCreateGenericTagTermBulkOperationMutationResolver;
        }
        return $this->payloadableCreateGenericTagTermBulkOperationMutationResolver;
    }
    final public function setRootDeleteGenericTagTermInputObjectTypeResolver(RootDeleteGenericTagTermInputObjectTypeResolver $rootDeleteGenericTagTermInputObjectTypeResolver): void
    {
        $this->rootDeleteGenericTagTermInputObjectTypeResolver = $rootDeleteGenericTagTermInputObjectTypeResolver;
    }
    final protected function getRootDeleteGenericTagTermInputObjectTypeResolver(): RootDeleteGenericTagTermInputObjectTypeResolver
    {
        if ($this->rootDeleteGenericTagTermInputObjectTypeResolver === null) {
            /** @var RootDeleteGenericTagTermInputObjectTypeResolver */
            $rootDeleteGenericTagTermInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericTagTermInputObjectTypeResolver::class);
            $this->rootDeleteGenericTagTermInputObjectTypeResolver = $rootDeleteGenericTagTermInputObjectTypeResolver;
        }
        return $this->rootDeleteGenericTagTermInputObjectTypeResolver;
    }
    final public function setRootUpdateGenericTagTermInputObjectTypeResolver(RootUpdateGenericTagTermInputObjectTypeResolver $rootUpdateGenericTagTermInputObjectTypeResolver): void
    {
        $this->rootUpdateGenericTagTermInputObjectTypeResolver = $rootUpdateGenericTagTermInputObjectTypeResolver;
    }
    final protected function getRootUpdateGenericTagTermInputObjectTypeResolver(): RootUpdateGenericTagTermInputObjectTypeResolver
    {
        if ($this->rootUpdateGenericTagTermInputObjectTypeResolver === null) {
            /** @var RootUpdateGenericTagTermInputObjectTypeResolver */
            $rootUpdateGenericTagTermInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericTagTermInputObjectTypeResolver::class);
            $this->rootUpdateGenericTagTermInputObjectTypeResolver = $rootUpdateGenericTagTermInputObjectTypeResolver;
        }
        return $this->rootUpdateGenericTagTermInputObjectTypeResolver;
    }
    final public function setRootCreateGenericTagTermInputObjectTypeResolver(RootCreateGenericTagTermInputObjectTypeResolver $rootCreateGenericTagTermInputObjectTypeResolver): void
    {
        $this->rootCreateGenericTagTermInputObjectTypeResolver = $rootCreateGenericTagTermInputObjectTypeResolver;
    }
    final protected function getRootCreateGenericTagTermInputObjectTypeResolver(): RootCreateGenericTagTermInputObjectTypeResolver
    {
        if ($this->rootCreateGenericTagTermInputObjectTypeResolver === null) {
            /** @var RootCreateGenericTagTermInputObjectTypeResolver */
            $rootCreateGenericTagTermInputObjectTypeResolver = $this->instanceManager->getInstance(RootCreateGenericTagTermInputObjectTypeResolver::class);
            $this->rootCreateGenericTagTermInputObjectTypeResolver = $rootCreateGenericTagTermInputObjectTypeResolver;
        }
        return $this->rootCreateGenericTagTermInputObjectTypeResolver;
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
        $addFieldsToQueryPayloadableTagMutations = $moduleConfiguration->addFieldsToQueryPayloadableTagMutations();
        return array_merge(
            [
                'createTag',
                'createTags',
            ],
            !$disableRedundantRootTypeMutationFields ? [
                'updateTag',
                'updateTags',
                'deleteTag',
                'deleteTags',
            ] : [],
            $addFieldsToQueryPayloadableTagMutations ? [
                'createTagMutationPayloadObjects',
            ] : [],
            $addFieldsToQueryPayloadableTagMutations && !$disableRedundantRootTypeMutationFields ? [
                'updateTagMutationPayloadObjects',
                'deleteTagMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createTag' => $this->__('Create a tag', 'tag-mutations'),
            'createTags' => $this->__('Create tags', 'tag-mutations'),
            'updateTag' => $this->__('Update a tag', 'tag-mutations'),
            'updateTags' => $this->__('Update tags', 'tag-mutations'),
            'deleteTag' => $this->__('Delete a tag', 'tag-mutations'),
            'deleteTags' => $this->__('Delete tags', 'tag-mutations'),
            'createTagMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `createGenericTag` mutation', 'tag-mutations'),
            'updateTagMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateGenericTag` mutation', 'tag-mutations'),
            'deleteTagMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteGenericTag` mutation', 'tag-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        if (!$usePayloadableTagMutations) {
            return match ($fieldName) {
                'createTag',
                'updateTag',
                'deleteTag'
                    => SchemaTypeModifiers::NONE,
                'createTags',
                'updateTags',
                'deleteTags'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'createTagMutationPayloadObjects',
            'updateTagMutationPayloadObjects',
            'deleteTagMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'createTag',
            'updateTag',
            'deleteTag'
                => SchemaTypeModifiers::NON_NULLABLE,
            'createTags',
            'updateTags',
            'deleteTags'
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
            'createTag' => [
                'input' => $this->getRootCreateGenericTagTermInputObjectTypeResolver(),
            ],
            'createTags'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootCreateGenericTagTermInputObjectTypeResolver()),
            'updateTag' => [
                'input' => $this->getRootUpdateGenericTagTermInputObjectTypeResolver(),
            ],
            'updateTags'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateGenericTagTermInputObjectTypeResolver()),
            'deleteTag' => [
                'input' => $this->getRootDeleteGenericTagTermInputObjectTypeResolver(),
            ],
            'deleteTags'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteGenericTagTermInputObjectTypeResolver()),
            'createTagMutationPayloadObjects',
            'updateTagMutationPayloadObjects',
            'deleteTagMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'createTagMutationPayloadObjects',
            'updateTagMutationPayloadObjects',
            'deleteTagMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'createTags',
            'updateTags',
            'deleteTags',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['createTag' => 'input'],
            ['updateTag' => 'input'],
            ['deleteTag' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'createTags',
            'updateTags',
            'deleteTags',
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
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        return match ($fieldName) {
            'createTag' => $usePayloadableTagMutations
                ? $this->getPayloadableCreateGenericTagTermMutationResolver()
                : $this->getCreateGenericTagTermMutationResolver(),
            'createTags' => $usePayloadableTagMutations
                ? $this->getPayloadableCreateGenericTagTermBulkOperationMutationResolver()
                : $this->getCreateGenericTagTermBulkOperationMutationResolver(),
            'updateTag' => $usePayloadableTagMutations
                ? $this->getPayloadableUpdateGenericTagTermMutationResolver()
                : $this->getUpdateGenericTagTermMutationResolver(),
            'updateTags' => $usePayloadableTagMutations
                ? $this->getPayloadableUpdateGenericTagTermBulkOperationMutationResolver()
                : $this->getUpdateGenericTagTermBulkOperationMutationResolver(),
            'deleteTag' => $usePayloadableTagMutations
                ? $this->getPayloadableDeleteGenericTagTermMutationResolver()
                : $this->getDeleteGenericTagTermMutationResolver(),
            'deleteTags' => $usePayloadableTagMutations
                ? $this->getPayloadableDeleteGenericTagTermBulkOperationMutationResolver()
                : $this->getDeleteGenericTagTermBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        if ($usePayloadableTagMutations) {
            return match ($fieldName) {
                'createTag',
                'createTags',
                'createTagMutationPayloadObjects'
                    => $this->getRootCreateGenericTagTermMutationPayloadObjectTypeResolver(),
                'updateTag',
                'updateTags',
                'updateTagMutationPayloadObjects'
                    => $this->getRootUpdateGenericTagTermMutationPayloadObjectTypeResolver(),
                'deleteTag',
                'deleteTags',
                'deleteTagMutationPayloadObjects'
                    => $this->getRootDeleteGenericTagTermMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'createTag',
            'createTags',
            'updateTag',
            'updateTags'
                => $this->getGenericTagObjectTypeResolver(),
            'deleteTag',
            'deleteTags'
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
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        if ($usePayloadableTagMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'createTag':
            case 'createTags':
            case 'updateTag':
            case 'updateTags':
            case 'deleteTag':
            case 'deleteTags':
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
            case 'createTagMutationPayloadObjects':
            case 'updateTagMutationPayloadObjects':
            case 'deleteTagMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
