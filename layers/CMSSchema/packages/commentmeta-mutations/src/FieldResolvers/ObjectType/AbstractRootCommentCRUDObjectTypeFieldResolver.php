<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\RootAddCommentMetaInputObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\RootDeleteCommentMetaInputObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\RootSetCommentMetaInputObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\RootUpdateCommentMetaInputObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\Module;
use PoPCMSSchema\CommentMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\AddCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\AddCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\DeleteCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\DeleteCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableAddCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableAddCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableDeleteCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableDeleteCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableSetCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableSetCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableUpdateCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableUpdateCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\SetCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\SetCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\UpdateCommentMetaBulkOperationMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\UpdateCommentMetaMutationResolver;
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

abstract class AbstractRootCommentCRUDObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?AddCommentMetaMutationResolver $addCommentMetaMutationResolver = null;
    private ?AddCommentMetaBulkOperationMutationResolver $addCommentMetaBulkOperationMutationResolver = null;
    private ?DeleteCommentMetaMutationResolver $deleteCommentMetaMutationResolver = null;
    private ?DeleteCommentMetaBulkOperationMutationResolver $deleteCommentMetaBulkOperationMutationResolver = null;
    private ?SetCommentMetaMutationResolver $setCommentMetaMutationResolver = null;
    private ?SetCommentMetaBulkOperationMutationResolver $setCommentMetaBulkOperationMutationResolver = null;
    private ?UpdateCommentMetaMutationResolver $updateCommentMetaMutationResolver = null;
    private ?UpdateCommentMetaBulkOperationMutationResolver $updateCommentMetaBulkOperationMutationResolver = null;
    private ?PayloadableDeleteCommentMetaMutationResolver $payloadableDeleteCommentMetaMutationResolver = null;
    private ?PayloadableDeleteCommentMetaBulkOperationMutationResolver $payloadableDeleteCommentMetaBulkOperationMutationResolver = null;
    private ?PayloadableSetCommentMetaMutationResolver $payloadableSetCommentMetaMutationResolver = null;
    private ?PayloadableSetCommentMetaBulkOperationMutationResolver $payloadableSetCommentMetaBulkOperationMutationResolver = null;
    private ?PayloadableUpdateCommentMetaMutationResolver $payloadableUpdateCommentMetaMutationResolver = null;
    private ?PayloadableUpdateCommentMetaBulkOperationMutationResolver $payloadableUpdateCommentMetaBulkOperationMutationResolver = null;
    private ?PayloadableAddCommentMetaMutationResolver $payloadableAddCommentMetaMutationResolver = null;
    private ?PayloadableAddCommentMetaBulkOperationMutationResolver $payloadableAddCommentMetaBulkOperationMutationResolver = null;
    private ?RootDeleteCommentMetaInputObjectTypeResolver $rootDeleteCommentMetaInputObjectTypeResolver = null;
    private ?RootSetCommentMetaInputObjectTypeResolver $rootSetCommentMetaInputObjectTypeResolver = null;
    private ?RootUpdateCommentMetaInputObjectTypeResolver $rootUpdateCommentMetaInputObjectTypeResolver = null;
    private ?RootAddCommentMetaInputObjectTypeResolver $rootAddCommentMetaInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getAddCommentMetaMutationResolver(): AddCommentMetaMutationResolver
    {
        if ($this->addCommentMetaMutationResolver === null) {
            /** @var AddCommentMetaMutationResolver */
            $addCommentMetaMutationResolver = $this->instanceManager->getInstance(AddCommentMetaMutationResolver::class);
            $this->addCommentMetaMutationResolver = $addCommentMetaMutationResolver;
        }
        return $this->addCommentMetaMutationResolver;
    }
    final protected function getAddCommentMetaBulkOperationMutationResolver(): AddCommentMetaBulkOperationMutationResolver
    {
        if ($this->addCommentMetaBulkOperationMutationResolver === null) {
            /** @var AddCommentMetaBulkOperationMutationResolver */
            $addCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(AddCommentMetaBulkOperationMutationResolver::class);
            $this->addCommentMetaBulkOperationMutationResolver = $addCommentMetaBulkOperationMutationResolver;
        }
        return $this->addCommentMetaBulkOperationMutationResolver;
    }
    final protected function getDeleteCommentMetaMutationResolver(): DeleteCommentMetaMutationResolver
    {
        if ($this->deleteCommentMetaMutationResolver === null) {
            /** @var DeleteCommentMetaMutationResolver */
            $deleteCommentMetaMutationResolver = $this->instanceManager->getInstance(DeleteCommentMetaMutationResolver::class);
            $this->deleteCommentMetaMutationResolver = $deleteCommentMetaMutationResolver;
        }
        return $this->deleteCommentMetaMutationResolver;
    }
    final protected function getDeleteCommentMetaBulkOperationMutationResolver(): DeleteCommentMetaBulkOperationMutationResolver
    {
        if ($this->deleteCommentMetaBulkOperationMutationResolver === null) {
            /** @var DeleteCommentMetaBulkOperationMutationResolver */
            $deleteCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteCommentMetaBulkOperationMutationResolver::class);
            $this->deleteCommentMetaBulkOperationMutationResolver = $deleteCommentMetaBulkOperationMutationResolver;
        }
        return $this->deleteCommentMetaBulkOperationMutationResolver;
    }
    final protected function getSetCommentMetaMutationResolver(): SetCommentMetaMutationResolver
    {
        if ($this->setCommentMetaMutationResolver === null) {
            /** @var SetCommentMetaMutationResolver */
            $setCommentMetaMutationResolver = $this->instanceManager->getInstance(SetCommentMetaMutationResolver::class);
            $this->setCommentMetaMutationResolver = $setCommentMetaMutationResolver;
        }
        return $this->setCommentMetaMutationResolver;
    }
    final protected function getSetCommentMetaBulkOperationMutationResolver(): SetCommentMetaBulkOperationMutationResolver
    {
        if ($this->setCommentMetaBulkOperationMutationResolver === null) {
            /** @var SetCommentMetaBulkOperationMutationResolver */
            $setCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(SetCommentMetaBulkOperationMutationResolver::class);
            $this->setCommentMetaBulkOperationMutationResolver = $setCommentMetaBulkOperationMutationResolver;
        }
        return $this->setCommentMetaBulkOperationMutationResolver;
    }
    final protected function getUpdateCommentMetaMutationResolver(): UpdateCommentMetaMutationResolver
    {
        if ($this->updateCommentMetaMutationResolver === null) {
            /** @var UpdateCommentMetaMutationResolver */
            $updateCommentMetaMutationResolver = $this->instanceManager->getInstance(UpdateCommentMetaMutationResolver::class);
            $this->updateCommentMetaMutationResolver = $updateCommentMetaMutationResolver;
        }
        return $this->updateCommentMetaMutationResolver;
    }
    final protected function getUpdateCommentMetaBulkOperationMutationResolver(): UpdateCommentMetaBulkOperationMutationResolver
    {
        if ($this->updateCommentMetaBulkOperationMutationResolver === null) {
            /** @var UpdateCommentMetaBulkOperationMutationResolver */
            $updateCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateCommentMetaBulkOperationMutationResolver::class);
            $this->updateCommentMetaBulkOperationMutationResolver = $updateCommentMetaBulkOperationMutationResolver;
        }
        return $this->updateCommentMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteCommentMetaMutationResolver(): PayloadableDeleteCommentMetaMutationResolver
    {
        if ($this->payloadableDeleteCommentMetaMutationResolver === null) {
            /** @var PayloadableDeleteCommentMetaMutationResolver */
            $payloadableDeleteCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentMetaMutationResolver::class);
            $this->payloadableDeleteCommentMetaMutationResolver = $payloadableDeleteCommentMetaMutationResolver;
        }
        return $this->payloadableDeleteCommentMetaMutationResolver;
    }
    final protected function getPayloadableDeleteCommentMetaBulkOperationMutationResolver(): PayloadableDeleteCommentMetaBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteCommentMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteCommentMetaBulkOperationMutationResolver */
            $payloadableDeleteCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentMetaBulkOperationMutationResolver::class);
            $this->payloadableDeleteCommentMetaBulkOperationMutationResolver = $payloadableDeleteCommentMetaBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteCommentMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableSetCommentMetaMutationResolver(): PayloadableSetCommentMetaMutationResolver
    {
        if ($this->payloadableSetCommentMetaMutationResolver === null) {
            /** @var PayloadableSetCommentMetaMutationResolver */
            $payloadableSetCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetCommentMetaMutationResolver::class);
            $this->payloadableSetCommentMetaMutationResolver = $payloadableSetCommentMetaMutationResolver;
        }
        return $this->payloadableSetCommentMetaMutationResolver;
    }
    final protected function getPayloadableSetCommentMetaBulkOperationMutationResolver(): PayloadableSetCommentMetaBulkOperationMutationResolver
    {
        if ($this->payloadableSetCommentMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableSetCommentMetaBulkOperationMutationResolver */
            $payloadableSetCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetCommentMetaBulkOperationMutationResolver::class);
            $this->payloadableSetCommentMetaBulkOperationMutationResolver = $payloadableSetCommentMetaBulkOperationMutationResolver;
        }
        return $this->payloadableSetCommentMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateCommentMetaMutationResolver(): PayloadableUpdateCommentMetaMutationResolver
    {
        if ($this->payloadableUpdateCommentMetaMutationResolver === null) {
            /** @var PayloadableUpdateCommentMetaMutationResolver */
            $payloadableUpdateCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentMetaMutationResolver::class);
            $this->payloadableUpdateCommentMetaMutationResolver = $payloadableUpdateCommentMetaMutationResolver;
        }
        return $this->payloadableUpdateCommentMetaMutationResolver;
    }
    final protected function getPayloadableUpdateCommentMetaBulkOperationMutationResolver(): PayloadableUpdateCommentMetaBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateCommentMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateCommentMetaBulkOperationMutationResolver */
            $payloadableUpdateCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentMetaBulkOperationMutationResolver::class);
            $this->payloadableUpdateCommentMetaBulkOperationMutationResolver = $payloadableUpdateCommentMetaBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateCommentMetaBulkOperationMutationResolver;
    }
    final protected function getPayloadableAddCommentMetaMutationResolver(): PayloadableAddCommentMetaMutationResolver
    {
        if ($this->payloadableAddCommentMetaMutationResolver === null) {
            /** @var PayloadableAddCommentMetaMutationResolver */
            $payloadableAddCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddCommentMetaMutationResolver::class);
            $this->payloadableAddCommentMetaMutationResolver = $payloadableAddCommentMetaMutationResolver;
        }
        return $this->payloadableAddCommentMetaMutationResolver;
    }
    final protected function getPayloadableAddCommentMetaBulkOperationMutationResolver(): PayloadableAddCommentMetaBulkOperationMutationResolver
    {
        if ($this->payloadableAddCommentMetaBulkOperationMutationResolver === null) {
            /** @var PayloadableAddCommentMetaBulkOperationMutationResolver */
            $payloadableAddCommentMetaBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableAddCommentMetaBulkOperationMutationResolver::class);
            $this->payloadableAddCommentMetaBulkOperationMutationResolver = $payloadableAddCommentMetaBulkOperationMutationResolver;
        }
        return $this->payloadableAddCommentMetaBulkOperationMutationResolver;
    }
    final protected function getRootDeleteCommentMetaInputObjectTypeResolver(): RootDeleteCommentMetaInputObjectTypeResolver
    {
        if ($this->rootDeleteCommentMetaInputObjectTypeResolver === null) {
            /** @var RootDeleteCommentMetaInputObjectTypeResolver */
            $rootDeleteCommentMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentMetaInputObjectTypeResolver::class);
            $this->rootDeleteCommentMetaInputObjectTypeResolver = $rootDeleteCommentMetaInputObjectTypeResolver;
        }
        return $this->rootDeleteCommentMetaInputObjectTypeResolver;
    }
    final protected function getRootSetCommentMetaInputObjectTypeResolver(): RootSetCommentMetaInputObjectTypeResolver
    {
        if ($this->rootSetCommentMetaInputObjectTypeResolver === null) {
            /** @var RootSetCommentMetaInputObjectTypeResolver */
            $rootSetCommentMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetCommentMetaInputObjectTypeResolver::class);
            $this->rootSetCommentMetaInputObjectTypeResolver = $rootSetCommentMetaInputObjectTypeResolver;
        }
        return $this->rootSetCommentMetaInputObjectTypeResolver;
    }
    final protected function getRootUpdateCommentMetaInputObjectTypeResolver(): RootUpdateCommentMetaInputObjectTypeResolver
    {
        if ($this->rootUpdateCommentMetaInputObjectTypeResolver === null) {
            /** @var RootUpdateCommentMetaInputObjectTypeResolver */
            $rootUpdateCommentMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateCommentMetaInputObjectTypeResolver::class);
            $this->rootUpdateCommentMetaInputObjectTypeResolver = $rootUpdateCommentMetaInputObjectTypeResolver;
        }
        return $this->rootUpdateCommentMetaInputObjectTypeResolver;
    }
    final protected function getRootAddCommentMetaInputObjectTypeResolver(): RootAddCommentMetaInputObjectTypeResolver
    {
        if ($this->rootAddCommentMetaInputObjectTypeResolver === null) {
            /** @var RootAddCommentMetaInputObjectTypeResolver */
            $rootAddCommentMetaInputObjectTypeResolver = $this->instanceManager->getInstance(RootAddCommentMetaInputObjectTypeResolver::class);
            $this->rootAddCommentMetaInputObjectTypeResolver = $rootAddCommentMetaInputObjectTypeResolver;
        }
        return $this->rootAddCommentMetaInputObjectTypeResolver;
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

    abstract protected function getCommentEntityName(): string;

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        $commentEntityName = $this->getCommentEntityName();
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        $disableRedundantRootTypeMutationFields = $engineModuleConfiguration->disableRedundantRootTypeMutationFields();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableCommentMetaMutations = $moduleConfiguration->addFieldsToQueryPayloadableCommentMetaMutations();
        return array_merge(
            !$disableRedundantRootTypeMutationFields ? [
                'add' . $commentEntityName . 'Meta',
                'add' . $commentEntityName . 'Metas',
                'update' . $commentEntityName . 'Meta',
                'update' . $commentEntityName . 'Metas',
                'delete' . $commentEntityName . 'Meta',
                'delete' . $commentEntityName . 'Metas',
                'set' . $commentEntityName . 'Meta',
                'set' . $commentEntityName . 'Metas',
            ] : [],
            $addFieldsToQueryPayloadableCommentMetaMutations && !$disableRedundantRootTypeMutationFields ? [
                'add' . $commentEntityName . 'MetaMutationPayloadObjects',
                'update' . $commentEntityName . 'MetaMutationPayloadObjects',
                'delete' . $commentEntityName . 'MetaMutationPayloadObjects',
                'set' . $commentEntityName . 'MetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $commentEntityName = $this->getCommentEntityName();
        return match ($fieldName) {
            'add' . $commentEntityName . 'Meta' => $this->__('Add meta to comment', 'comment-mutations'),
            'add' . $commentEntityName . 'Metas' => $this->__('Add meta to comments', 'comment-mutations'),
            'update' . $commentEntityName . 'Meta' => $this->__('Update meta from comment', 'comment-mutations'),
            'update' . $commentEntityName . 'Metas' => $this->__('Update meta from comments', 'comment-mutations'),
            'delete' . $commentEntityName . 'Meta' => $this->__('Delete meta from comment', 'comment-mutations'),
            'delete' . $commentEntityName . 'Metas' => $this->__('Delete meta from comments', 'comment-mutations'),
            'set' . $commentEntityName . 'Meta' => $this->__('Set meta on comment', 'comment-mutations'),
            'set' . $commentEntityName . 'Metas' => $this->__('Set meta on comments', 'comment-mutations'),
            'add' . $commentEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCommentMeta` mutation', 'comment-mutations'),
            'update' . $commentEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCommentMeta` mutation', 'comment-mutations'),
            'delete' . $commentEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteCommentMeta` mutation', 'comment-mutations'),
            'set' . $commentEntityName . 'MetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `setCommentMeta` mutation', 'comment-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        $commentEntityName = $this->getCommentEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        if (!$usePayloadableCommentMetaMutations) {
            return match ($fieldName) {
                'add' . $commentEntityName . 'Meta',
                'update' . $commentEntityName . 'Meta',
                'delete' . $commentEntityName . 'Meta',
                'set' . $commentEntityName . 'Meta'
                    => SchemaTypeModifiers::NONE,
                'add' . $commentEntityName . 'Metas',
                'update' . $commentEntityName . 'Metas',
                'delete' . $commentEntityName . 'Metas',
                'set' . $commentEntityName . 'Metas'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'add' . $commentEntityName . 'MetaMutationPayloadObjects',
            'update' . $commentEntityName . 'MetaMutationPayloadObjects',
            'delete' . $commentEntityName . 'MetaMutationPayloadObjects',
            'set' . $commentEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'add' . $commentEntityName . 'Meta',
            'update' . $commentEntityName . 'Meta',
            'delete' . $commentEntityName . 'Meta',
            'set' . $commentEntityName . 'Meta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'add' . $commentEntityName . 'Metas',
            'update' . $commentEntityName . 'Metas',
            'delete' . $commentEntityName . 'Metas',
            'set' . $commentEntityName . 'Metas'
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
        $commentEntityName = $this->getCommentEntityName();
        return match ($fieldName) {
            'add' . $commentEntityName . 'Meta' => [
                'input' => $this->getRootAddCommentMetaInputObjectTypeResolver(),
            ],
            'add' . $commentEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddCommentMetaInputObjectTypeResolver()),
            'update' . $commentEntityName . 'Meta' => [
                'input' => $this->getRootUpdateCommentMetaInputObjectTypeResolver(),
            ],
            'update' . $commentEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateCommentMetaInputObjectTypeResolver()),
            'delete' . $commentEntityName . 'Meta' => [
                'input' => $this->getRootDeleteCommentMetaInputObjectTypeResolver(),
            ],
            'delete' . $commentEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteCommentMetaInputObjectTypeResolver()),
            'set' . $commentEntityName . 'Meta' => [
                'input' => $this->getRootSetCommentMetaInputObjectTypeResolver(),
            ],
            'set' . $commentEntityName . 'Metas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootSetCommentMetaInputObjectTypeResolver()),
            'add' . $commentEntityName . 'MetaMutationPayloadObjects',
            'update' . $commentEntityName . 'MetaMutationPayloadObjects',
            'delete' . $commentEntityName . 'MetaMutationPayloadObjects',
            'set' . $commentEntityName . 'MetaMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $commentEntityName = $this->getCommentEntityName();
        if (
            in_array($fieldName, [
            'add' . $commentEntityName . 'MetaMutationPayloadObjects',
            'update' . $commentEntityName . 'MetaMutationPayloadObjects',
            'delete' . $commentEntityName . 'MetaMutationPayloadObjects',
            'set' . $commentEntityName . 'MetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'add' . $commentEntityName . 'Metas',
            'update' . $commentEntityName . 'Metas',
            'delete' . $commentEntityName . 'Metas',
            'set' . $commentEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['add' . $commentEntityName . 'Meta' => 'input'],
            ['update' . $commentEntityName . 'Meta' => 'input'],
            ['delete' . $commentEntityName . 'Meta' => 'input'],
            ['set' . $commentEntityName . 'Meta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $commentEntityName = $this->getCommentEntityName();
        if (
            in_array($fieldName, [
            'add' . $commentEntityName . 'Metas',
            'update' . $commentEntityName . 'Metas',
            'delete' . $commentEntityName . 'Metas',
            'set' . $commentEntityName . 'Metas',
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        $commentEntityName = $this->getCommentEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        return match ($fieldName) {
            'add' . $commentEntityName . 'Meta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableAddCommentMetaMutationResolver()
                : $this->getAddCommentMetaMutationResolver(),
            'add' . $commentEntityName . 'Metas' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableAddCommentMetaBulkOperationMutationResolver()
                : $this->getAddCommentMetaBulkOperationMutationResolver(),
            'update' . $commentEntityName . 'Meta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableUpdateCommentMetaMutationResolver()
                : $this->getUpdateCommentMetaMutationResolver(),
            'update' . $commentEntityName . 'Metas' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableUpdateCommentMetaBulkOperationMutationResolver()
                : $this->getUpdateCommentMetaBulkOperationMutationResolver(),
            'delete' . $commentEntityName . 'Meta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableDeleteCommentMetaMutationResolver()
                : $this->getDeleteCommentMetaMutationResolver(),
            'delete' . $commentEntityName . 'Metas' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableDeleteCommentMetaBulkOperationMutationResolver()
                : $this->getDeleteCommentMetaBulkOperationMutationResolver(),
            'set' . $commentEntityName . 'Meta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableSetCommentMetaMutationResolver()
                : $this->getSetCommentMetaMutationResolver(),
            'set' . $commentEntityName . 'Metas' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableSetCommentMetaBulkOperationMutationResolver()
                : $this->getSetCommentMetaBulkOperationMutationResolver(),
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
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        if ($usePayloadableCommentMetaMutations) {
            return $validationCheckpoints;
        }

        $commentEntityName = $this->getCommentEntityName();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'add' . $commentEntityName . 'Meta':
            case 'add' . $commentEntityName . 'Metas':
            case 'update' . $commentEntityName . 'Meta':
            case 'update' . $commentEntityName . 'Metas':
            case 'delete' . $commentEntityName . 'Meta':
            case 'delete' . $commentEntityName . 'Metas':
            case 'set' . $commentEntityName . 'Meta':
            case 'set' . $commentEntityName . 'Metas':
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
        $commentEntityName = $this->getCommentEntityName();
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'add' . $commentEntityName . 'MetaMutationPayloadObjects':
            case 'update' . $commentEntityName . 'MetaMutationPayloadObjects':
            case 'delete' . $commentEntityName . 'MetaMutationPayloadObjects':
            case 'set' . $commentEntityName . 'MetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
