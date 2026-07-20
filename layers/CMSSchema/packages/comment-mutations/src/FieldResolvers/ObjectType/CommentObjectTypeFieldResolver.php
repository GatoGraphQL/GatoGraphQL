<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\DeleteCommentMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableDeleteCommentMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableUpdateCommentMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\UpdateCommentMutationResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CommentDeleteInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CommentReplyInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CommentUpdateInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties as SchemaCommonsMutationInputProperties;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\BulkOperationDecoratorObjectTypeFieldResolverTrait;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class CommentObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?AddCommentToCustomPostBulkOperationMutationResolver $addCommentToCustomPostBulkOperationMutationResolver = null;
    private ?CommentReplyInputObjectTypeResolver $commentReplyInputObjectTypeResolver = null;
    private ?CommentReplyMutationPayloadObjectTypeResolver $commentReplyMutationPayloadObjectTypeResolver = null;
    private ?PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver = null;
    private ?PayloadableAddCommentToCustomPostBulkOperationMutationResolver $payloadableAddCommentToCustomPostBulkOperationMutationResolver = null;
    private ?CommentUpdateInputObjectTypeResolver $commentUpdateInputObjectTypeResolver = null;
    private ?CommentDeleteInputObjectTypeResolver $commentDeleteInputObjectTypeResolver = null;
    private ?CommentUpdateMutationPayloadObjectTypeResolver $commentUpdateMutationPayloadObjectTypeResolver = null;
    private ?CommentDeleteMutationPayloadObjectTypeResolver $commentDeleteMutationPayloadObjectTypeResolver = null;
    private ?UpdateCommentMutationResolver $updateCommentMutationResolver = null;
    private ?PayloadableUpdateCommentMutationResolver $payloadableUpdateCommentMutationResolver = null;
    private ?DeleteCommentMutationResolver $deleteCommentMutationResolver = null;
    private ?PayloadableDeleteCommentMutationResolver $payloadableDeleteCommentMutationResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        if ($this->commentTypeAPI === null) {
            /** @var CommentTypeAPIInterface */
            $commentTypeAPI = $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
            $this->commentTypeAPI = $commentTypeAPI;
        }
        return $this->commentTypeAPI;
    }
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
    final protected function getCommentReplyInputObjectTypeResolver(): CommentReplyInputObjectTypeResolver
    {
        if ($this->commentReplyInputObjectTypeResolver === null) {
            /** @var CommentReplyInputObjectTypeResolver */
            $commentReplyInputObjectTypeResolver = $this->instanceManager->getInstance(CommentReplyInputObjectTypeResolver::class);
            $this->commentReplyInputObjectTypeResolver = $commentReplyInputObjectTypeResolver;
        }
        return $this->commentReplyInputObjectTypeResolver;
    }
    final protected function getCommentReplyMutationPayloadObjectTypeResolver(): CommentReplyMutationPayloadObjectTypeResolver
    {
        if ($this->commentReplyMutationPayloadObjectTypeResolver === null) {
            /** @var CommentReplyMutationPayloadObjectTypeResolver */
            $commentReplyMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentReplyMutationPayloadObjectTypeResolver::class);
            $this->commentReplyMutationPayloadObjectTypeResolver = $commentReplyMutationPayloadObjectTypeResolver;
        }
        return $this->commentReplyMutationPayloadObjectTypeResolver;
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

    final protected function getCommentUpdateInputObjectTypeResolver(): CommentUpdateInputObjectTypeResolver
    {
        if ($this->commentUpdateInputObjectTypeResolver === null) {
            /** @var CommentUpdateInputObjectTypeResolver */
            $commentUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(CommentUpdateInputObjectTypeResolver::class);
            $this->commentUpdateInputObjectTypeResolver = $commentUpdateInputObjectTypeResolver;
        }
        return $this->commentUpdateInputObjectTypeResolver;
    }
    final protected function getCommentDeleteInputObjectTypeResolver(): CommentDeleteInputObjectTypeResolver
    {
        if ($this->commentDeleteInputObjectTypeResolver === null) {
            /** @var CommentDeleteInputObjectTypeResolver */
            $commentDeleteInputObjectTypeResolver = $this->instanceManager->getInstance(CommentDeleteInputObjectTypeResolver::class);
            $this->commentDeleteInputObjectTypeResolver = $commentDeleteInputObjectTypeResolver;
        }
        return $this->commentDeleteInputObjectTypeResolver;
    }
    final protected function getCommentUpdateMutationPayloadObjectTypeResolver(): CommentUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->commentUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var CommentUpdateMutationPayloadObjectTypeResolver */
            $commentUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentUpdateMutationPayloadObjectTypeResolver::class);
            $this->commentUpdateMutationPayloadObjectTypeResolver = $commentUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->commentUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getCommentDeleteMutationPayloadObjectTypeResolver(): CommentDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->commentDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var CommentDeleteMutationPayloadObjectTypeResolver */
            $commentDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentDeleteMutationPayloadObjectTypeResolver::class);
            $this->commentDeleteMutationPayloadObjectTypeResolver = $commentDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->commentDeleteMutationPayloadObjectTypeResolver;
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
    final protected function getPayloadableUpdateCommentMutationResolver(): PayloadableUpdateCommentMutationResolver
    {
        if ($this->payloadableUpdateCommentMutationResolver === null) {
            /** @var PayloadableUpdateCommentMutationResolver */
            $payloadableUpdateCommentMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentMutationResolver::class);
            $this->payloadableUpdateCommentMutationResolver = $payloadableUpdateCommentMutationResolver;
        }
        return $this->payloadableUpdateCommentMutationResolver;
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
    final protected function getPayloadableDeleteCommentMutationResolver(): PayloadableDeleteCommentMutationResolver
    {
        if ($this->payloadableDeleteCommentMutationResolver === null) {
            /** @var PayloadableDeleteCommentMutationResolver */
            $payloadableDeleteCommentMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentMutationResolver::class);
            $this->payloadableDeleteCommentMutationResolver = $payloadableDeleteCommentMutationResolver;
        }
        return $this->payloadableDeleteCommentMutationResolver;
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
            CommentObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'reply',
            'replyWithComments',
            'update',
            'delete',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'reply' => $this->__('Reply a comment with another comment', 'gatographql'),
            'replyWithComments' => $this->__('Reply a comment with other comments', 'gatographql'),
            'update' => $this->__('Update the comment', 'gatographql'),
            'delete' => $this->__('Delete the comment', 'gatographql'),
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
                'reply',
                'update' => SchemaTypeModifiers::NONE,
                'delete' => SchemaTypeModifiers::NON_NULLABLE,
                'replyWithComments' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'reply',
            'update',
            'delete' => SchemaTypeModifiers::NON_NULLABLE,
            'replyWithComments' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'reply' => [
                MutationInputProperties::INPUT => $this->getCommentReplyInputObjectTypeResolver(),
            ],
            'replyWithComments' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getCommentReplyInputObjectTypeResolver()),
            'update' => [
                MutationInputProperties::INPUT => $this->getCommentUpdateInputObjectTypeResolver(),
            ],
            'delete' => [
                MutationInputProperties::INPUT => $this->getCommentDeleteInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['reply' => MutationInputProperties::INPUT],
            ['update' => MutationInputProperties::INPUT] => SchemaTypeModifiers::MANDATORY,
            ['replyWithComments' => SchemaCommonsMutationInputProperties::INPUTS] => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     */
    public function validateMutationOnObject(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return match ($fieldName) {
            'reply',
            'replyWithComments',
            'update',
            'delete'
                => true,
            default
                => parent::validateMutationOnObject($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     */
    public function prepareFieldArgsForMutationForObject(
        array $fieldArgsForMutationForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): array {
        $fieldArgsForMutationForObject = parent::prepareFieldArgsForMutationForObject(
            $fieldArgsForMutationForObject,
            $objectTypeResolver,
            $field,
            $object,
        );
        $comment = $object;
        switch ($field->getName()) {
            case 'reply':
                /** @var stdClass */
                $input = &$fieldArgsForMutationForObject[MutationInputProperties::INPUT];
                $input->{MutationInputProperties::CUSTOMPOST_ID} = $this->getCommentTypeAPI()->getCommentCustomPostID($comment);
                $input->{MutationInputProperties::PARENT_COMMENT_ID} = $objectTypeResolver->getID($comment);
                break;
            case 'replyWithComments':
                /** @var stdClass[] */
                $inputs = $fieldArgsForMutationForObject[SchemaCommonsMutationInputProperties::INPUTS];
                $customPostID = $this->getCommentTypeAPI()->getCommentCustomPostID($comment);
                $parentCommentID = $objectTypeResolver->getID($comment);
                foreach ($inputs as &$input) {
                    $input->{MutationInputProperties::CUSTOMPOST_ID} = $customPostID;
                    $input->{MutationInputProperties::PARENT_COMMENT_ID} = $parentCommentID;
                }
                break;
            case 'update':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::ID} = $objectTypeResolver->getID($comment);
                break;
            case 'delete':
                /**
                 * The "input" is optional, as it only carries the `force`
                 * input field. Hence create it if it was not provided.
                 */
                if (!isset($fieldArgsForMutationForObject[MutationInputProperties::INPUT])) {
                    $fieldArgsForMutationForObject[MutationInputProperties::INPUT] = new stdClass();
                }
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::ID} = $objectTypeResolver->getID($comment);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        return match ($fieldName) {
            'reply' => $usePayloadableCommentMutations
                ? $this->getPayloadableAddCommentToCustomPostMutationResolver()
                : $this->getAddCommentToCustomPostMutationResolver(),
            'replyWithComments' => $usePayloadableCommentMutations
                ? $this->getPayloadableAddCommentToCustomPostBulkOperationMutationResolver()
                : $this->getAddCommentToCustomPostBulkOperationMutationResolver(),
            'update' => $usePayloadableCommentMutations
                ? $this->getPayloadableUpdateCommentMutationResolver()
                : $this->getUpdateCommentMutationResolver(),
            'delete' => $usePayloadableCommentMutations
                ? $this->getPayloadableDeleteCommentMutationResolver()
                : $this->getDeleteCommentMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        return match ($fieldName) {
            'reply',
            'replyWithComments'
                => $usePayloadableCommentMutations
                    ? $this->getCommentReplyMutationPayloadObjectTypeResolver()
                    : $this->getCommentObjectTypeResolver(),
            'update' => $usePayloadableCommentMutations
                ? $this->getCommentUpdateMutationPayloadObjectTypeResolver()
                : $this->getCommentObjectTypeResolver(),
            'delete' => $usePayloadableCommentMutations
                ? $this->getCommentDeleteMutationPayloadObjectTypeResolver()
                : $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
