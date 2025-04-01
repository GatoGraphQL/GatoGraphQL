<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\RootAddTagTermMetaInputObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\RootDeleteTagTermMetaInputObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\RootSetTagTermMetaInputObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\RootUpdateTagTermMetaInputObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\Module;
use PoPCMSSchema\TagMetaMutations\ModuleConfiguration;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\AddTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\AddTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\DeleteTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\DeleteTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableAddTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableAddTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableDeleteTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableDeleteTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableSetTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableSetTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableUpdateTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableUpdateTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\SetTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\SetTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\UpdateTagTermMetaBulkOperationMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\UpdateTagTermMetaMutationResolver;
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

abstract class AbstractRootTagCRUDObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?AddTagTermMetaMutationResolver $addTagTermMetaMutationResolver = null;
    private ?AddTagTermMetaBulkOperationMutationResolver $addTagTermMetaBulkOperationMutationResolver = null;
    private ?DeleteTagTermMetaMutationResolver $deleteTagTermMetaMutationResolver = null;
    private ?DeleteTagTermMetaBulkOperationMutationResolver $deleteTagTermMetaBulkOperationMutationResolver = null;
    private ?SetTagTermMetaMutationResolver $setTagTermMetaMutationResolver = null;
    private ?SetTagTermMetaBulkOperationMutationResolver $setTagTermMetaBulkOperationMutationResolver = null;
    private ?UpdateTagTermMetaMutationResolver $updateTagTermMetaMutationResolver = null;
    private ?UpdateTagTermMetaBulkOperationMutationResolver $updateTagTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteTagTermMetaMutationResolver $payloadableDeleteTagTermMetaMutationResolver = null;
    private ?PayloadableDeleteTagTermMetaBulkOperationMutationResolver $payloadableDeleteTagTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableSetTagTermMetaMutationResolver $payloadableSetTagTermMetaMutationResolver = null;
    private ?PayloadableSetTagTermMetaBulkOperationMutationResolver $payloadableSetTagTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateTagTermMetaMutationResolver $payloadableUpdateTagTermMetaMutationResolver = null;
    private ?PayloadableUpdateTagTermMetaBulkOperationMutationResolver $payloadableUpdateTagTermMetaBulkOperationMutationResolver = null;
    private ?PayloadableAddTagTermMetaMutationResolver $payloadableAddTagTermMetaMutationResolver = null;
    private ?PayloadableAddTagTermMetaBulkOperationMutationResolver $payloadableAddTagTermMetaBulkOperationMutationResolver = null;
    private ?RootDeleteTagTermMetaInputObjectTypeResolver $rootDeleteTagTermMetaInputObjectTypeResolver = null;
    private ?RootSetTagTermMetaInputObjectTypeResolver $rootSetTagTermMetaInputObjectTypeResolver = null;
    private ?RootUpdateTagTermMetaInputObjectTypeResolver $rootUpdateTagTermMetaInputObjectTypeResolver = null;
    private ?RootAddTagTermMetaInputObjectTypeResolver $rootAddTagTermMetaInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getAddTagTermMetaMutationResolver(): AddTagTermMetaMutationResolver
    {
        if ($this->addTagTermMetaMutationResolver === null) {
            /** @var AddTagTermMetaMutationResolver */
            $addTagTermMetaMutationResolver = $this->instanceManager->getInstance(AddTagTermMetaMutationResolver::class);
            $this->addTagTermMetaMutationResolver = $addTagTermMetaMutationResolver;
        }
        return $this->addTagTermMetaMutationResolver;
    }
    final protected function getAddTagTermMetaBulkOperationMutationResolver(): AddTagTermMetaBulkOperationMutationResolver
    {
        if ($this->addTagTermMetaBulkOperationMutationResolver === null) {
            /** @var AddTagTermMetaBulkOperationMutationResolver */
            $addTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(AddTagTermMetaBulkOperationMutationResolver::class);
            $this->addTagTermMetaBulkOperationMutationResolver = $addTagTermMetaBulkOperationMutationResolver;
        }
        return $this->addTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getDeleteTagTermMetaMutationResolver(): DeleteTagTermMetaMutationResolver
    {
        if ($this->deleteTagTermMetaMutationResolver === null) {
            /** @var DeleteTagTermMetaMutationResolver */
            $deleteTagTermMetaMutationResolver = $this->instanceManager->getInstance(DeleteTagTermMetaMutationResolver::class);
            $this->deleteTagTermMetaMutationResolver = $deleteTagTermMetaMutationResolver;
        }
        return $this->deleteTagTermMetaMutationResolver;
    }
    final protected function getDeleteTagTermMetaBulkOperationMutationResolver(): DeleteTagTermMetaBulkOperationMutationResolver
    {
        if ($this->deleteTagTermMetaBulkOperationMutationResolver === null) {
            /** @var DeleteTagTermMetaBulkOperationMutationResolver */
            $deleteTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteTagTermMetaBulkOperationMutationResolver::class);
            $this->deleteTagTermMetaBulkOperationMutationResolver = $deleteTagTermMetaBulkOperationMutationResolver;
        }
        return $this->deleteTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getSetTagTermMetaMutationResolver(): SetTagTermMetaMutationResolver
    {
        if ($this->setTagTermMetaMutationResolver === null) {
            /** @var SetTagTermMetaMutationResolver */
            $setTagTermMetaMutationResolver = $this->instanceManager->getInstance(SetTagTermMetaMutationResolver::class);
            $this->setTagTermMetaMutationResolver = $setTagTermMetaMutationResolver;
        }
        return $this->setTagTermMetaMutationResolver;
    }
    final protected function getSetTagTermMetaBulkOperationMutationResolver(): SetTagTermMetaBulkOperationMutationResolver
    {
        if ($this->setTagTermMetaBulkOperationMutationResolver === null) {
            /** @var SetTagTermMetaBulkOperationMutationResolver */
            $setTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(SetTagTermMetaBulkOperationMutationResolver::class);
            $this->setTagTermMetaBulkOperationMutationResolver = $setTagTermMetaBulkOperationMutationResolver;
        }
        return $this->setTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getUpdateTagTermMetaMutationResolver(): UpdateTagTermMetaMutationResolver
    {
        if ($this->updateTagTermMetaMutationResolver === null) {
            /** @var UpdateTagTermMetaMutationResolver */
            $updateTagTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateTagTermMetaMutationResolver::class);
            $this->updateTagTermMetaMutationResolver = $updateTagTermMetaMutationResolver;
        }
        return $this->updateTagTermMetaMutationResolver;
    }
    final protected function getUpdateTagTermMetaBulkOperationMutationResolver(): UpdateTagTermMetaBulkOperationMutationResolver
    {
        if ($this->updateTagTermMetaBulkOperationMutationResolver === null) {
            /** @var UpdateTagTermMetaBulkOperationMutationResolver */
            $updateTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateTagTermMetaBulkOperationMutationResolver::class);
            $this->updateTagTermMetaBulkOperationMutationResolver = $updateTagTermMetaBulkOperationMutationResolver;
        }
        return $this->updateTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteTagTermMetaMutationResolver(): PayloadableDeleteTagTermMetaMutationResolver
    {
        if ($this->payloadableDeleteTagTermMetaMutationResolver === null) {
            /** @var PayloadableDeleteTagTermMetaMutationResolver */
            $payloadableDeleteTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteTagTermMetaMutationResolver::class);
            $this->payloadableDeleteTagTermMetaMutationResolver = $payloadableDeleteTagTermMetaMutationResolver;
        }
        return $this->payloadableDeleteTagTermMetaMutationResolver;
    }
    final protected function getPayloadableDeleteTagTermMetaBulkOperationMutationResolver(): PayloadableDeleteTagTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteTagTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteTagTermMetaBulkOperationMutationResolver */
            $payloadableDeleteTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteTagTermMetaBulkOperationMutationResolver::class);
            $this->payloadableDeleteTagTermMetaBulkOperationMutationResolver = $payloadableDeleteTagTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableSetTagTermMetaMutationResolver(): PayloadableSetTagTermMetaMutationResolver
    {
        if ($this->payloadableSetTagTermMetaMutationResolver === null) {
            /** @var PayloadableSetTagTermMetaMutationResolver */
            $payloadableSetTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagTermMetaMutationResolver::class);
            $this->payloadableSetTagTermMetaMutationResolver = $payloadableSetTagTermMetaMutationResolver;
        }
        return $this->payloadableSetTagTermMetaMutationResolver;
    }
    final protected function getPayloadableSetTagTermMetaBulkOperationMutationResolver(): PayloadableSetTagTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableSetTagTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableSetTagTermMetaBulkOperationMutationResolver */
            $payloadableSetTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagTermMetaBulkOperationMutationResolver::class);
            $this->payloadableSetTagTermMetaBulkOperationMutationResolver = $payloadableSetTagTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableSetTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateTagTermMetaMutationResolver(): PayloadableUpdateTagTermMetaMutationResolver
    {
        if ($this->payloadableUpdateTagTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateTagTermMetaMutationResolver */
            $payloadableUpdateTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateTagTermMetaMutationResolver::class);
            $this->payloadableUpdateTagTermMetaMutationResolver = $payloadableUpdateTagTermMetaMutationResolver;
        }
        return $this->payloadableUpdateTagTermMetaMutationResolver;
    }
    final protected function getPayloadableUpdateTagTermMetaBulkOperationMutationResolver(): PayloadableUpdateTagTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateTagTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateTagTermMetaBulkOperationMutationResolver */
            $payloadableUpdateTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateTagTermMetaBulkOperationMutationResolver::class);
            $this->payloadableUpdateTagTermMetaBulkOperationMutationResolver = $payloadableUpdateTagTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableAddTagTermMetaMutationResolver(): PayloadableAddTagTermMetaMutationResolver
    {
        if ($this->payloadableAddTagTermMetaMutationResolver === null) {
            /** @var PayloadableAddTagTermMetaMutationResolver */
            $payloadableAddTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddTagTermMetaMutationResolver::class);
            $this->payloadableAddTagTermMetaMutationResolver = $payloadableAddTagTermMetaMutationResolver;
        }
        return $this->payloadableAddTagTermMetaMutationResolver;
    }
    final protected function getPayloadableAddTagTermMetaBulkOperationMutationResolver(): PayloadableAddTagTermMetaBulkOperationMutationResolver
    {
        if ($this->payloadableAddTagTermMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableAddTagTermMetaBulkOperationMutationResolver */
            $payloadableAddTagTermMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableAddTagTermMetaBulkOperationMutationResolver::class);
            $this->payloadableAddTagTermMetaBulkOperationMutationResolver = $payloadableAddTagTermMetaBulkOperationMutationResolver;
        }
        return $this->payloadableAddTagTermMetaBulkOperationMutationResolver;
    }
    final protected function getRootDeleteTagTermMetaInputObjectTypeResolver(): RootDeleteTagTermMetaInputObjectTypeResolver
    {
        if ($this->rootDeleteTagTermMetaInputObjectTypeResolver === null) {
            /** @var RootDeleteTagTermMetaInputObjectTypeResolver */
            $rootDeleteTagTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteTagTermMetaInputObjectTypeResolver::class);
            $this->rootDeleteTagTermMetaInputObjectTypeResolver = $rootDeleteTagTermMetaInputObjectTypeResolver;
        }
        return $this->rootDeleteTagTermMetaInputObjectTypeResolver;
    }
    final protected function getRootSetTagTermMetaInputObjectTypeResolver(): RootSetTagTermMetaInputObjectTypeResolver
    {
        if ($this->rootSetTagTermMetaInputObjectTypeResolver === null) {
            /** @var RootSetTagTermMetaInputObjectTypeResolver */
            $rootSetTagTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetTagTermMetaInputObjectTypeResolver::class);
            $this->rootSetTagTermMetaInputObjectTypeResolver = $rootSetTagTermMetaInputObjectTypeResolver;
        }
        return $this->rootSetTagTermMetaInputObjectTypeResolver;
    }
    final protected function getRootUpdateTagTermMetaInputObjectTypeResolver(): RootUpdateTagTermMetaInputObjectTypeResolver
    {
        if ($this->rootUpdateTagTermMetaInputObjectTypeResolver === null) {
            /** @var RootUpdateTagTermMetaInputObjectTypeResolver */
            $rootUpdateTagTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateTagTermMetaInputObjectTypeResolver::class);
            $this->rootUpdateTagTermMetaInputObjectTypeResolver = $rootUpdateTagTermMetaInputObjectTypeResolver;
        }
        return $this->rootUpdateTagTermMetaInputObjectTypeResolver;
    }
    final protected function getRootAddTagTermMetaInputObjectTypeResolver(): RootAddTagTermMetaInputObjectTypeResolver
    {
        if ($this->rootAddTagTermMetaInputObjectTypeResolver === null) {
            /** @var RootAddTagTermMetaInputObjectTypeResolver */
            $rootAddTagTermMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootAddTagTermMetaInputObjectTypeResolver::class);
            $this->rootAddTagTermMetaInputObjectTypeResolver = $rootAddTagTermMetaInputObjectTypeResolver;
        }
        return $this->rootAddTagTermMetaInputObjectTypeResolver;
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

    abstract protected function getTagEntityName(): string;

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        $tagEntityName = $this->getTagEntityName();
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        $disableRedundantRootTypeMutationFields = $engineModuleConfiguration->disableRedundantRootTypeMutationFields();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableTagMetaMutations = $moduleConfiguration->addFieldsToQueryPayloadableTagMetaMutations();
        return array_merge(
            !$disableRedundantRootTypeMutationFields ? [
                'add' . $tagEntityName . 'Meta',
                'add' . $tagEntityName . 'Metas',
                'update' . $tagEntityName . 'Meta',
                'update' . $tagEntityName . 'Metas',
                'delete' . $tagEntityName . 'Meta',
                'delete' . $tagEntityName . 'Metas',
                'set' . $tagEntityName . 'Meta',
                'set' . $tagEntityName . 'Metas',
            ] : [],
            $addFieldsToQueryPayloadableTagMetaMutations && !$disableRedundantRootTypeMutationFields ? [
                'add' . $tagEntityName . 'MetaMutationPayloadObjects',
                'update' . $tagEntityName . 'MetaMutationPayloadObjects',
                'delete' . $tagEntityName . 'MetaMutationPayloadObjects',
                'set' . $tagEntityName . 'MetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $tagEntityName = $this->getTagEntityName();
        return match ($fieldName) {
            'add' . $tagEntityName . 'Meta' => $this->__('Add meta to tag', 'tag-mutations'),
            'add' . $tagEntityName . 'Metas' => $this->__('Add meta to tags', 'tag-mutations'),
            'update' . $tagEntityName . 'Meta' => $this->__('Update meta from tag', 'tag-mutations'),
            'update' . $tagEntityName . 'Metas' => $this->__('Update meta from tags', 'tag-mutations'),
            'delete' . $tagEntityName . 'Meta' => $this->__('Delete meta from tag', 'tag-mutations'),
            'delete' . $tagEntityName . 'Metas' => $this->__('Delete meta from tags', 'tag-mutations'),
            'set' . $tagEntityName . 'Meta' => $this->__('Set meta on tag', 'tag-mutations'),
            'set' . $tagEntityName . 'Metas' => $this->__('Set meta on tags', 'tag-mutations'),
            'add' . $tagEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addTagMeta` mutation', 'tag-mutations'),
            'update' . $tagEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateTagMeta` mutation', 'tag-mutations'),
            'delete' . $tagEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteTagMeta` mutation', 'tag-mutations'),
            'set' . $tagEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `setTagMeta` mutation', 'tag-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        $tagEntityName = $this->getTagEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        if (!$usePayloadableTagMetaMutations) {
            return match ($fieldName) {
                'add' . $tagEntityName . 'Meta',
                'update' . $tagEntityName . 'Meta',
                'delete' . $tagEntityName . 'Meta',
                'set' . $tagEntityName . 'Meta'
                    => SchemaTypeModifiers::NONE,
                'add' . $tagEntityName . 'Metas',
                'update' . $tagEntityName . 'Metas',
                'delete' . $tagEntityName . 'Metas',
                'set' . $tagEntityName . 'Metas'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'add' . $tagEntityName . 'MetaMutationPayloadObjects',
            'update' . $tagEntityName . 'MetaMutationPayloadObjects',
            'delete' . $tagEntityName . 'MetaMutationPayloadObjects',
            'set' . $tagEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'add' . $tagEntityName . 'Meta',
            'update' . $tagEntityName . 'Meta',
            'delete' . $tagEntityName . 'Meta',
            'set' . $tagEntityName . 'Meta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'add' . $tagEntityName . 'Metas',
            'update' . $tagEntityName . 'Metas',
            'delete' . $tagEntityName . 'Metas',
            'set' . $tagEntityName . 'Metas'
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
        $tagEntityName = $this->getTagEntityName();
        return match ($fieldName) {
            'add' . $tagEntityName . 'Meta' => [
                'input' => $this->getRootAddTagTermMetaInputObjectTypeResolver(),
            ],
            'add' . $tagEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddTagTermMetaInputObjectTypeResolver()),
            'update' . $tagEntityName . 'Meta' => [
                'input' => $this->getRootUpdateTagTermMetaInputObjectTypeResolver(),
            ],
            'update' . $tagEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateTagTermMetaInputObjectTypeResolver()),
            'delete' . $tagEntityName . 'Meta' => [
                'input' => $this->getRootDeleteTagTermMetaInputObjectTypeResolver(),
            ],
            'delete' . $tagEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteTagTermMetaInputObjectTypeResolver()),
            'set' . $tagEntityName . 'Meta' => [
                'input' => $this->getRootSetTagTermMetaInputObjectTypeResolver(),
            ],
            'set' . $tagEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootSetTagTermMetaInputObjectTypeResolver()),
            'add' . $tagEntityName . 'MetaMutationPayloadObjects',
            'update' . $tagEntityName . 'MetaMutationPayloadObjects',
            'delete' . $tagEntityName . 'MetaMutationPayloadObjects',
            'set' . $tagEntityName . 'MetaMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $tagEntityName = $this->getTagEntityName();
        if (
            in_array($fieldName, [
            'add' . $tagEntityName . 'MetaMutationPayloadObjects',
            'update' . $tagEntityName . 'MetaMutationPayloadObjects',
            'delete' . $tagEntityName . 'MetaMutationPayloadObjects',
            'set' . $tagEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'add' . $tagEntityName . 'Metas',
            'update' . $tagEntityName . 'Metas',
            'delete' . $tagEntityName . 'Metas',
            'set' . $tagEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['add' . $tagEntityName . 'Meta' => 'input'],
            ['update' . $tagEntityName . 'Meta' => 'input'],
            ['delete' . $tagEntityName . 'Meta' => 'input'],
            ['set' . $tagEntityName . 'Meta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $tagEntityName = $this->getTagEntityName();
        if (
            in_array($fieldName, [
            'add' . $tagEntityName . 'Metas',
            'update' . $tagEntityName . 'Metas',
            'delete' . $tagEntityName . 'Metas',
            'set' . $tagEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        $tagEntityName = $this->getTagEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        return match ($fieldName) {
            'add' . $tagEntityName . 'Meta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableAddTagTermMetaMutationResolver()
                : $this->getAddTagTermMetaMutationResolver(),
            'add' . $tagEntityName . 'Metas' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableAddTagTermMetaBulkOperationMutationResolver()
                : $this->getAddTagTermMetaBulkOperationMutationResolver(),
            'update' . $tagEntityName . 'Meta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableUpdateTagTermMetaMutationResolver()
                : $this->getUpdateTagTermMetaMutationResolver(),
            'update' . $tagEntityName . 'Metas' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableUpdateTagTermMetaBulkOperationMutationResolver()
                : $this->getUpdateTagTermMetaBulkOperationMutationResolver(),
            'delete' . $tagEntityName . 'Meta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableDeleteTagTermMetaMutationResolver()
                : $this->getDeleteTagTermMetaMutationResolver(),
            'delete' . $tagEntityName . 'Metas' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableDeleteTagTermMetaBulkOperationMutationResolver()
                : $this->getDeleteTagTermMetaBulkOperationMutationResolver(),
            'set' . $tagEntityName . 'Meta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableSetTagTermMetaMutationResolver()
                : $this->getSetTagTermMetaMutationResolver(),
            'set' . $tagEntityName . 'Metas' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableSetTagTermMetaBulkOperationMutationResolver()
                : $this->getSetTagTermMetaBulkOperationMutationResolver(),
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
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        if ($usePayloadableTagMetaMutations) {
            return $validationCheckpoints;
        }

        $tagEntityName = $this->getTagEntityName();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'add' . $tagEntityName . 'Meta':
            case 'add' . $tagEntityName . 'Metas':
            case 'update' . $tagEntityName . 'Meta':
            case 'update' . $tagEntityName . 'Metas':
            case 'delete' . $tagEntityName . 'Meta':
            case 'delete' . $tagEntityName . 'Metas':
            case 'set' . $tagEntityName . 'Meta':
            case 'set' . $tagEntityName . 'Metas':
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
        $tagEntityName = $this->getTagEntityName();
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'add' . $tagEntityName . 'MetaMutationPayloadObjects':
            case 'update' . $tagEntityName . 'MetaMutationPayloadObjects':
            case 'delete' . $tagEntityName . 'MetaMutationPayloadObjects':
            case 'set' . $tagEntityName . 'MetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
