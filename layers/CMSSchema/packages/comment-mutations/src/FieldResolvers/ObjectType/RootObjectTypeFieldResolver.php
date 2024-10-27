<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootAddCommentToCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootReplyCommentInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
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
            ],
            $addFieldsToQueryPayloadableCommentMutations ? [
                'addCommentToCustomPostMutationPayloadObjects',
                'replyCommentMutationPayloadObjects',
            ] : [],
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addCommentToCustomPost' => $this->__('Add a comment to a custom post', 'comment-mutations'),
            'addCommentToCustomPosts' => $this->__('Add comments to a custom post', 'comment-mutations'),
            'replyComment' => $this->__('Reply a comment with another comment', 'comment-mutations'),
            'replyComments' => $this->__('Reply comment with other comments', 'comment-mutations'),
            'addCommentToCustomPostMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `addCommentToCustomPost` mutation', 'comment-mutations'),
            'replyCommentMutationPayloadObjects' => $this->__('Retrieve the payload objects from a recently-executed `replyComment` mutation', 'comment-mutations'),
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
                'replyComment'
                    => SchemaTypeModifiers::NONE,
                'addCommentToCustomPosts',
                'replyComments'
                    => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            'addCommentToCustomPostMutationPayloadObjects',
            'replyCommentMutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            'addCommentToCustomPost',
            'replyComment'
                => SchemaTypeModifiers::NON_NULLABLE,
            'addCommentToCustomPosts',
            'replyComments'
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
            'addCommentToCustomPostMutationPayloadObjects',
            'replyCommentMutationPayloadObjects'
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
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            'addCommentToCustomPosts',
            'replyComments',
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            ['addCommentToCustomPost' => MutationInputProperties::INPUT],
            ['replyComment' => MutationInputProperties::INPUT]
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
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addCommentToCustomPost',
            'addCommentToCustomPosts',
            'replyComment',
            'replyComments'
                => $this->getCommentObjectTypeResolver(),
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
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
