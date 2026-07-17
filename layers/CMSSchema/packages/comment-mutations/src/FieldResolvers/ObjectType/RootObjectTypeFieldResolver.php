<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\DeleteCommentBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\DeleteCommentMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableDeleteCommentBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableDeleteCommentMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableUpdateCommentBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableUpdateCommentMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\UpdateCommentBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\UpdateCommentMutationResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootAddCommentToCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootDeleteCommentInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootUpdateCommentInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootReplyCommentInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootDeleteCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootUpdateCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootReplyCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\BulkOperationDecoratorObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
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

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?AddCommentToCustomPostBulkOperationMutationResolver $addCommentToCustomPostBulkOperationMutationResolver = null;
    private ?RootAddCommentToCustomPostInputObjectTypeResolver $rootAddCommentToCustomPostInputObjectTypeResolver = null;
    private ?RootReplyCommentInputObjectTypeResolver $rootReplyCommentInputObjectTypeResolver = null;
    private ?RootAddCommentToCustomPostMutationPayloadObjectTypeResolver $rootAddCommentToCustomPostMutationPayloadObjectTypeResolver = null;
    private ?RootReplyCommentMutationPayloadObjectTypeResolver $rootReplyCommentMutationPayloadObjectTypeResolver = null;
    private ?PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver = null;
    private ?PayloadableAddCommentToCustomPostBulkOperationMutationResolver $payloadableAddCommentToCustomPostBulkOperationMutationResolver = null;
    private ?RootUpdateCommentInputObjectTypeResolver $rootUpdateCommentInputObjectTypeResolver = null;
    private ?RootDeleteCommentInputObjectTypeResolver $rootDeleteCommentInputObjectTypeResolver = null;
    private ?RootUpdateCommentMutationPayloadObjectTypeResolver $rootUpdateCommentMutationPayloadObjectTypeResolver = null;
    private ?RootDeleteCommentMutationPayloadObjectTypeResolver $rootDeleteCommentMutationPayloadObjectTypeResolver = null;
    private ?UpdateCommentMutationResolver $updateCommentMutationResolver = null;
    private ?UpdateCommentBulkOperationMutationResolver $updateCommentBulkOperationMutationResolver = null;
    private ?PayloadableUpdateCommentMutationResolver $payloadableUpdateCommentMutationResolver = null;
    private ?PayloadableUpdateCommentBulkOperationMutationResolver $payloadableUpdateCommentBulkOperationMutationResolver = null;
    private ?DeleteCommentMutationResolver $deleteCommentMutationResolver = null;
    private ?DeleteCommentBulkOperationMutationResolver $deleteCommentBulkOperationMutationResolver = null;
    private ?PayloadableDeleteCommentMutationResolver $payloadableDeleteCommentMutationResolver = null;
    private ?PayloadableDeleteCommentBulkOperationMutationResolver $payloadableDeleteCommentBulkOperationMutationResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final protected function getAddCommentToCustomPostMutationResolver(): AddCommentToCustomPostMutationResolver
    {
        if ($this->addCommentToCustomPostMutationResolver === null) {
            /** @var AddCommentToCustomPostMutationResolver */
            $addCommentToCustomPostMutationResolver = $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
            $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
        }
        return $this->addCommentToCustomPostMutationResolver;
    }
    final protected function getAddCommentToCustomPostBulkOperationMutationResolver(): AddCommentToCustomPostBulkOperationMutationResolver
    {
        if ($this->addCommentToCustomPostBulkOperationMutationResolver === null) {
            /** @var AddCommentToCustomPostBulkOperationMutationResolver */
            $addCommentToCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(AddCommentToCustomPostBulkOperationMutationResolver::class);
            $this->addCommentToCustomPostBulkOperationMutationResolver = $addCommentToCustomPostBulkOperationMutationResolver;
        }
        return $this->addCommentToCustomPostBulkOperationMutationResolver;
    }
    final protected function getRootAddCommentToCustomPostInputObjectTypeResolver(): RootAddCommentToCustomPostInputObjectTypeResolver
    {
        if ($this->rootAddCommentToCustomPostInputObjectTypeResolver === null) {
            /** @var RootAddCommentToCustomPostInputObjectTypeResolver */
            $rootAddCommentToCustomPostInputObjectTypeResolver = $this->instanceManager->getInstance(RootAddCommentToCustomPostInputObjectTypeResolver::class);
            $this->rootAddCommentToCustomPostInputObjectTypeResolver = $rootAddCommentToCustomPostInputObjectTypeResolver;
        }
        return $this->rootAddCommentToCustomPostInputObjectTypeResolver;
    }
    final protected function getRootReplyCommentInputObjectTypeResolver(): RootReplyCommentInputObjectTypeResolver
    {
        if ($this->rootReplyCommentInputObjectTypeResolver === null) {
            /** @var RootReplyCommentInputObjectTypeResolver */
            $rootReplyCommentInputObjectTypeResolver = $this->instanceManager->getInstance(RootReplyCommentInputObjectTypeResolver::class);
            $this->rootReplyCommentInputObjectTypeResolver = $rootReplyCommentInputObjectTypeResolver;
        }
        return $this->rootReplyCommentInputObjectTypeResolver;
    }
    final protected function getRootAddCommentToCustomPostMutationPayloadObjectTypeResolver(): RootAddCommentToCustomPostMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddCommentToCustomPostMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddCommentToCustomPostMutationPayloadObjectTypeResolver */
            $rootAddCommentToCustomPostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationPayloadObjectTypeResolver::class);
            $this->rootAddCommentToCustomPostMutationPayloadObjectTypeResolver = $rootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootReplyCommentMutationPayloadObjectTypeResolver(): RootReplyCommentMutationPayloadObjectTypeResolver
    {
        if ($this->rootReplyCommentMutationPayloadObjectTypeResolver === null) {
            /** @var RootReplyCommentMutationPayloadObjectTypeResolver */
            $rootReplyCommentMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootReplyCommentMutationPayloadObjectTypeResolver::class);
            $this->rootReplyCommentMutationPayloadObjectTypeResolver = $rootReplyCommentMutationPayloadObjectTypeResolver;
        }
        return $this->rootReplyCommentMutationPayloadObjectTypeResolver;
    }
    final protected function getPayloadableAddCommentToCustomPostMutationResolver(): PayloadableAddCommentToCustomPostMutationResolver
    {
        if ($this->payloadableAddCommentToCustomPostMutationResolver === null) {
            /** @var PayloadableAddCommentToCustomPostMutationResolver */
            $payloadableAddCommentToCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableAddCommentToCustomPostMutationResolver::class);
            $this->payloadableAddCommentToCustomPostMutationResolver = $payloadableAddCommentToCustomPostMutationResolver;
        }
        return $this->payloadableAddCommentToCustomPostMutationResolver;
    }
    final protected function getPayloadableAddCommentToCustomPostBulkOperationMutationResolver(): PayloadableAddCommentToCustomPostBulkOperationMutationResolver
    {
        if ($this->payloadableAddCommentToCustomPostBulkOperationMutationResolver === null) {
            /** @var PayloadableAddCommentToCustomPostBulkOperationMutationResolver */
            $payloadableAddCommentToCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableAddCommentToCustomPostBulkOperationMutationResolver::class);
            $this->payloadableAddCommentToCustomPostBulkOperationMutationResolver = $payloadableAddCommentToCustomPostBulkOperationMutationResolver;
        }
        return $this->payloadableAddCommentToCustomPostBulkOperationMutationResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    final protected function getRootUpdateCommentInputObjectTypeResolver(): RootUpdateCommentInputObjectTypeResolver
    {
        if ($this->rootUpdateCommentInputObjectTypeResolver === null) {
            /** @var RootUpdateCommentInputObjectTypeResolver */
            $rootUpdateCommentInputObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateCommentInputObjectTypeResolver::class);
            $this->rootUpdateCommentInputObjectTypeResolver = $rootUpdateCommentInputObjectTypeResolver;
        }
        return $this->rootUpdateCommentInputObjectTypeResolver;
    }
    final protected function getRootDeleteCommentInputObjectTypeResolver(): RootDeleteCommentInputObjectTypeResolver
    {
        if ($this->rootDeleteCommentInputObjectTypeResolver === null) {
            /** @var RootDeleteCommentInputObjectTypeResolver */
            $rootDeleteCommentInputObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentInputObjectTypeResolver::class);
            $this->rootDeleteCommentInputObjectTypeResolver = $rootDeleteCommentInputObjectTypeResolver;
        }
        return $this->rootDeleteCommentInputObjectTypeResolver;
    }
    final protected function getRootUpdateCommentMutationPayloadObjectTypeResolver(): RootUpdateCommentMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateCommentMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateCommentMutationPayloadObjectTypeResolver */
            $rootUpdateCommentMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateCommentMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateCommentMutationPayloadObjectTypeResolver = $rootUpdateCommentMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateCommentMutationPayloadObjectTypeResolver;
    }
    final protected function getRootDeleteCommentMutationPayloadObjectTypeResolver(): RootDeleteCommentMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteCommentMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteCommentMutationPayloadObjectTypeResolver */
            $rootDeleteCommentMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteCommentMutationPayloadObjectTypeResolver = $rootDeleteCommentMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteCommentMutationPayloadObjectTypeResolver;
    }
    final protected function getUpdateCommentMutationResolver(): UpdateCommentMutationResolver
    {
        if ($this->updateCommentMutationResolver === null) {
            /** @var UpdateCommentMutationResolver */
            $updateCommentMutationResolver = $this->instanceManager->getInstance(UpdateCommentMutationResolver::class);
            $this->updateCommentMutationResolver = $updateCommentMutationResolver;
        }
        return $this->updateCommentMutationResolver;
    }
    final protected function getUpdateCommentBulkOperationMutationResolver(): UpdateCommentBulkOperationMutationResolver
    {
        if ($this->updateCommentBulkOperationMutationResolver === null) {
            /** @var UpdateCommentBulkOperationMutationResolver */
            $updateCommentBulkOperationMutationResolver = $this->instanceManager->getInstance(UpdateCommentBulkOperationMutationResolver::class);
            $this->updateCommentBulkOperationMutationResolver = $updateCommentBulkOperationMutationResolver;
        }
        return $this->updateCommentBulkOperationMutationResolver;
    }
    final protected function getPayloadableUpdateCommentMutationResolver(): PayloadableUpdateCommentMutationResolver
    {
        if ($this->payloadableUpdateCommentMutationResolver === null) {
            /** @var PayloadableUpdateCommentMutationResolver */
            $payloadableUpdateCommentMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentMutationResolver::class);
            $this->payloadableUpdateCommentMutationResolver = $payloadableUpdateCommentMutationResolver;
        }
        return $this->payloadableUpdateCommentMutationResolver;
    }
    final protected function getPayloadableUpdateCommentBulkOperationMutationResolver(): PayloadableUpdateCommentBulkOperationMutationResolver
    {
        if ($this->payloadableUpdateCommentBulkOperationMutationResolver === null) {
            /** @var PayloadableUpdateCommentBulkOperationMutationResolver */
            $payloadableUpdateCommentBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentBulkOperationMutationResolver::class);
            $this->payloadableUpdateCommentBulkOperationMutationResolver = $payloadableUpdateCommentBulkOperationMutationResolver;
        }
        return $this->payloadableUpdateCommentBulkOperationMutationResolver;
    }
    final protected function getDeleteCommentMutationResolver(): DeleteCommentMutationResolver
    {
        if ($this->deleteCommentMutationResolver === null) {
            /** @var DeleteCommentMutationResolver */
            $deleteCommentMutationResolver = $this->instanceManager->getInstance(DeleteCommentMutationResolver::class);
            $this->deleteCommentMutationResolver = $deleteCommentMutationResolver;
        }
        return $this->deleteCommentMutationResolver;
    }
    final protected function getDeleteCommentBulkOperationMutationResolver(): DeleteCommentBulkOperationMutationResolver
    {
        if ($this->deleteCommentBulkOperationMutationResolver === null) {
            /** @var DeleteCommentBulkOperationMutationResolver */
            $deleteCommentBulkOperationMutationResolver = $this->instanceManager->getInstance(DeleteCommentBulkOperationMutationResolver::class);
            $this->deleteCommentBulkOperationMutationResolver = $deleteCommentBulkOperationMutationResolver;
        }
        return $this->deleteCommentBulkOperationMutationResolver;
    }
    final protected function getPayloadableDeleteCommentMutationResolver(): PayloadableDeleteCommentMutationResolver
    {
        if ($this->payloadableDeleteCommentMutationResolver === null) {
            /** @var PayloadableDeleteCommentMutationResolver */
            $payloadableDeleteCommentMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentMutationResolver::class);
            $this->payloadableDeleteCommentMutationResolver = $payloadableDeleteCommentMutationResolver;
        }
        return $this->payloadableDeleteCommentMutationResolver;
    }
    final protected function getPayloadableDeleteCommentBulkOperationMutationResolver(): PayloadableDeleteCommentBulkOperationMutationResolver
    {
        if ($this->payloadableDeleteCommentBulkOperationMutationResolver === null) {
            /** @var PayloadableDeleteCommentBulkOperationMutationResolver */
            $payloadableDeleteCommentBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentBulkOperationMutationResolver::class);
            $this->payloadableDeleteCommentBulkOperationMutationResolver = $payloadableDeleteCommentBulkOperationMutationResolver;
        }
        return $this->payloadableDeleteCommentBulkOperationMutationResolver;
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
        if ($engineModuleConfiguration->disableRedundantRootTypeMutationFields()) {
            return [];
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableCommentMutations = $moduleConfiguration->addFieldsToQueryPayloadableCommentMutations();
        return array_merge(
            [
                'addCommentToCustomPost',
                'addCommentToCustomPosts',
                'replyComment',
                'replyComments',
                'updateComment',
                'updateComments',
                'deleteComment',
                'deleteComments',
            ],
            $addFieldsToQueryPayloadableCommentMutations ? [
                'addCommentToCustomPostMutationPayloadObjects',
                'replyCommentMutationPayloadObjects',
                'updateCommentMutationPayloadObjects',
                'deleteCommentMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addCommentToCustomPost' => $this->__('Add a comment to a custom post', 'gatographql'),
            'addCommentToCustomPosts' => $this->__('Add comments to a custom post', 'gatographql'),
            'replyComment' => $this->__('Reply a comment with another comment', 'gatographql'),
            'replyComments' => $this->__('Reply comment with other comments', 'gatographql'),
            'addCommentToCustomPostMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCommentToCustomPost` mutation', 'gatographql'),
            'replyCommentMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `replyComment` mutation', 'gatographql'),
            'updateComment' => $this->__('Update a comment', 'gatographql'),
            'updateComments' => $this->__('Update comments', 'gatographql'),
            'deleteComment' => $this->__('Delete a comment', 'gatographql'),
            'deleteComments' => $this->__('Delete comments', 'gatographql'),
            'updateCommentMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `updateComment` mutation', 'gatographql'),
            'deleteCommentMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `deleteComment` mutation', 'gatographql'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        if (!$usePayloadableCommentMutations) {
            return match ($fieldName) {
                'addCommentToCustomPost',
                'replyComment',
                'updateComment'
                    => SchemaTypeModifiers::NONE,
                'deleteComment'
                    => SchemaTypeModifiers::NON_NULLABLE,
                'addCommentToCustomPosts',
                'replyComments'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                'updateComments',
                'deleteComments'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'addCommentToCustomPostMutationPayloadObjects',
            'replyCommentMutationPayloadObjects',
            'updateCommentMutationPayloadObjects',
            'deleteCommentMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment',
            'updateComment',
            'deleteComment'
                => SchemaTypeModifiers::NON_NULLABLE,
            'addCommentToCustomPosts',
            'replyComments',
            'updateComments',
            'deleteComments'
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
            'addCommentToCustomPost' => [
                MutationInputProperties::INPUT => $this->getRootAddCommentToCustomPostInputObjectTypeResolver(),
            ],
            'addCommentToCustomPosts' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootAddCommentToCustomPostInputObjectTypeResolver()),
            'replyComment' => [
                MutationInputProperties::INPUT => $this->getRootReplyCommentInputObjectTypeResolver(),
            ],
            'replyComments' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootReplyCommentInputObjectTypeResolver()),
            'updateComment' => [
                MutationInputProperties::INPUT => $this->getRootUpdateCommentInputObjectTypeResolver(),
            ],
            'updateComments' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootUpdateCommentInputObjectTypeResolver()),
            'deleteComment' => [
                MutationInputProperties::INPUT => $this->getRootDeleteCommentInputObjectTypeResolver(),
            ],
            'deleteComments' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getRootDeleteCommentInputObjectTypeResolver()),
            'addCommentToCustomPostMutationPayloadObjects',
            'replyCommentMutationPayloadObjects',
            'updateCommentMutationPayloadObjects',
            'deleteCommentMutationPayloadObjects'
                => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            'addCommentToCustomPostMutationPayloadObjects',
            'replyCommentMutationPayloadObjects',
            'updateCommentMutationPayloadObjects',
            'deleteCommentMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'addCommentToCustomPosts',
            'replyComments',
            'updateComments',
            'deleteComments',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['addCommentToCustomPost' => MutationInputProperties::INPUT],
            ['replyComment' => MutationInputProperties::INPUT],
            ['updateComment' => MutationInputProperties::INPUT],
            ['deleteComment' => MutationInputProperties::INPUT]
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            'addCommentToCustomPosts',
            'replyComments',
            'updateComments',
            'deleteComments',
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
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment'
                => $usePayloadableCommentMutations
                    ? $this->getPayloadableAddCommentToCustomPostMutationResolver()
                    : $this->getAddCommentToCustomPostMutationResolver(),
            'addCommentToCustomPosts',
            'replyComments'
                => $usePayloadableCommentMutations
                    ? $this->getPayloadableAddCommentToCustomPostBulkOperationMutationResolver()
                    : $this->getAddCommentToCustomPostBulkOperationMutationResolver(),
            'updateComment' => $usePayloadableCommentMutations
                ? $this->getPayloadableUpdateCommentMutationResolver()
                : $this->getUpdateCommentMutationResolver(),
            'updateComments' => $usePayloadableCommentMutations
                ? $this->getPayloadableUpdateCommentBulkOperationMutationResolver()
                : $this->getUpdateCommentBulkOperationMutationResolver(),
            'deleteComment' => $usePayloadableCommentMutations
                ? $this->getPayloadableDeleteCommentMutationResolver()
                : $this->getDeleteCommentMutationResolver(),
            'deleteComments' => $usePayloadableCommentMutations
                ? $this->getPayloadableDeleteCommentBulkOperationMutationResolver()
                : $this->getDeleteCommentBulkOperationMutationResolver(),
            default
                => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        if ($usePayloadableCommentMutations) {
            return match ($fieldName) {
                'addCommentToCustomPost',
                'addCommentToCustomPosts',
                'addCommentToCustomPostMutationPayloadObjects'
                    => $this->getRootAddCommentToCustomPostMutationPayloadObjectTypeResolver(),
                'replyComment',
                'replyComments',
                'replyCommentMutationPayloadObjects'
                    => $this->getRootReplyCommentMutationPayloadObjectTypeResolver(),
                'updateComment',
                'updateComments',
                'updateCommentMutationPayloadObjects'
                    => $this->getRootUpdateCommentMutationPayloadObjectTypeResolver(),
                'deleteComment',
                'deleteComments',
                'deleteCommentMutationPayloadObjects'
                    => $this->getRootDeleteCommentMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addCommentToCustomPost',
            'addCommentToCustomPosts',
            'replyComment',
            'replyComments',
            'updateComment',
            'updateComments'
                => $this->getCommentObjectTypeResolver(),
            'deleteComment',
            'deleteComments'
                => $this->getBooleanScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'addCommentToCustomPostMutationPayloadObjects':
            case 'replyCommentMutationPayloadObjects':
            case 'updateCommentMutationPayloadObjects':
            case 'deleteCommentMutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
