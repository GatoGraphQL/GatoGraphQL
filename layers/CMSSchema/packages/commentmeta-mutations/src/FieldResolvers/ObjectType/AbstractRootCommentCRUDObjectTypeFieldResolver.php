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
        $addFieldsToQueryPayloadableCommentMetaMutations = $moduleConfiguration->addFieldsToQueryPayloadableCommentMetaMutations();
        return array_merge(
            !$disableRedundantRootTypeMutationFields ? [
                'addCommentMeta',
                'addCommentMetas',
                'updateCommentMeta',
                'updateCommentMetas',
                'deleteCommentMeta',
                'deleteCommentMetas',
                'setCommentMeta',
                'setCommentMetas',
            ] : [],
            $addFieldsToQueryPayloadableCommentMetaMutations && !$disableRedundantRootTypeMutationFields ? [
                'addCommentMetaMutationPayloadObjects',
                'updateCommentMetaMutationPayloadObjects',
                'deleteCommentMetaMutationPayloadObjects',
                'setCommentMetaMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addCommentMeta' => $this->__('Add meta to comment', 'comment-mutations'),
            'addCommentMetas' => $this->__('Add meta to comments', 'comment-mutations'),
            'updateCommentMeta' => $this->__('Update meta from comment', 'comment-mutations'),
            'updateCommentMetas' => $this->__('Update meta from comments', 'comment-mutations'),
            'deleteCommentMeta' => $this->__('Delete meta from comment', 'comment-mutations'),
            'deleteCommentMetas' => $this->__('Delete meta from comments', 'comment-mutations'),
            'setCommentMeta' => $this->__('Set meta on comment', 'comment-mutations'),
            'setCommentMetas' => $this->__('Set meta on comments', 'comment-mutations'),
            'addCommentMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCommentMeta` mutation', 'comment-mutations'),
            'updateCommentMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateCommentMeta` mutation', 'comment-mutations'),
            'deleteCommentMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteCommentMeta` mutation', 'comment-mutations'),
            'setCommentMetaMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `setCommentMeta` mutation', 'comment-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        if (!$usePayloadableCommentMetaMutations) {
            return match ($fieldName) {
                'addCommentMeta',
                'updateCommentMeta',
                'deleteCommentMeta',
                'setCommentMeta'
                    => SchemaTypeModifiers::NONE,
                'addCommentMetas',
                'updateCommentMetas',
                'deleteCommentMetas',
                'setCommentMetas'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'addCommentMetaMutationPayloadObjects',
            'updateCommentMetaMutationPayloadObjects',
            'deleteCommentMetaMutationPayloadObjects',
            'setCommentMetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'addCommentMeta',
            'updateCommentMeta',
            'deleteCommentMeta',
            'setCommentMeta'
                => SchemaTypeModifiers::NON_NULLABLE,
            'addCommentMetas',
            'updateCommentMetas',
            'deleteCommentMetas',
            'setCommentMetas'
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
            'addCommentMeta' => [
                'input' => $this->getRootAddCommentMetaInputObjectTypeResolver(),
            ],
            'addCommentMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddCommentMetaInputObjectTypeResolver()),
            'updateCommentMeta' => [
                'input' => $this->getRootUpdateCommentMetaInputObjectTypeResolver(),
            ],
            'updateCommentMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateCommentMetaInputObjectTypeResolver()),
            'deleteCommentMeta' => [
                'input' => $this->getRootDeleteCommentMetaInputObjectTypeResolver(),
            ],
            'deleteCommentMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteCommentMetaInputObjectTypeResolver()),
            'setCommentMeta' => [
                'input' => $this->getRootSetCommentMetaInputObjectTypeResolver(),
            ],
            'setCommentMetas'
                => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootSetCommentMetaInputObjectTypeResolver()),
            'addCommentMetaMutationPayloadObjects',
            'updateCommentMetaMutationPayloadObjects',
            'deleteCommentMetaMutationPayloadObjects',
            'setCommentMetaMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'addCommentMetaMutationPayloadObjects',
            'updateCommentMetaMutationPayloadObjects',
            'deleteCommentMetaMutationPayloadObjects',
            'setCommentMetaMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'addCommentMetas',
            'updateCommentMetas',
            'deleteCommentMetas',
            'setCommentMetas',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['addCommentMeta' => 'input'],
            ['updateCommentMeta' => 'input'],
            ['deleteCommentMeta' => 'input'],
            ['setCommentMeta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'addCommentMetas',
            'updateCommentMetas',
            'deleteCommentMetas',
            'setCommentMetas',
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
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        return match ($fieldName) {
            'addCommentMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableAddCommentMetaMutationResolver()
                : $this->getAddCommentMetaMutationResolver(),
            'addCommentMetas' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableAddCommentMetaBulkOperationMutationResolver()
                : $this->getAddCommentMetaBulkOperationMutationResolver(),
            'updateCommentMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableUpdateCommentMetaMutationResolver()
                : $this->getUpdateCommentMetaMutationResolver(),
            'updateCommentMetas' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableUpdateCommentMetaBulkOperationMutationResolver()
                : $this->getUpdateCommentMetaBulkOperationMutationResolver(),
            'deleteCommentMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableDeleteCommentMetaMutationResolver()
                : $this->getDeleteCommentMetaMutationResolver(),
            'deleteCommentMetas' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableDeleteCommentMetaBulkOperationMutationResolver()
                : $this->getDeleteCommentMetaBulkOperationMutationResolver(),
            'setCommentMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableSetCommentMetaMutationResolver()
                : $this->getSetCommentMetaMutationResolver(),
            'setCommentMetas' => $usePayloadableCommentMetaMutations
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

        switch ($fieldDataAccessor->getFieldName()) {
            case 'addCommentMeta':
            case 'addCommentMetas':
            case 'updateCommentMeta':
            case 'updateCommentMetas':
            case 'deleteCommentMeta':
            case 'deleteCommentMetas':
            case 'setCommentMeta':
            case 'setCommentMetas':
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
            case 'addCommentMetaMutationPayloadObjects':
            case 'updateCommentMetaMutationPayloadObjects':
            case 'deleteCommentMetaMutationPayloadObjects':
            case 'setCommentMetaMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
