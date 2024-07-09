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
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CustomPostAddCommentInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\FieldResolvers\ObjectType\MaybeCommentableCustomPostObjectTypeFieldResolverTrait;
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
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

abstract class AbstractAddCommentToCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MaybeCommentableCustomPostObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;

    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?AddCommentToCustomPostBulkOperationMutationResolver $addCommentToCustomPostBulkOperationMutationResolver = null;
    private ?CustomPostAddCommentInputObjectTypeResolver $customPostAddCommentInputObjectTypeResolver = null;
    private ?CustomPostAddCommentMutationPayloadObjectTypeResolver $customPostAddCommentMutationPayloadObjectTypeResolver = null;
    private ?PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver = null;
    private ?PayloadableAddCommentToCustomPostBulkOperationMutationResolver $payloadableAddCommentToCustomPostBulkOperationMutationResolver = null;
    private ?CommentTypeAPIInterface $commentTypeAPI = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
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
    final public function setAddCommentToCustomPostMutationResolver(AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver): void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
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
    final public function setAddCommentToCustomPostBulkOperationMutationResolver(AddCommentToCustomPostBulkOperationMutationResolver $addCommentToCustomPostBulkOperationMutationResolver): void
    {
        $this->addCommentToCustomPostBulkOperationMutationResolver = $addCommentToCustomPostBulkOperationMutationResolver;
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
    final public function setCustomPostAddCommentInputObjectTypeResolver(CustomPostAddCommentInputObjectTypeResolver $customPostAddCommentInputObjectTypeResolver): void
    {
        $this->customPostAddCommentInputObjectTypeResolver = $customPostAddCommentInputObjectTypeResolver;
    }
    final protected function getCustomPostAddCommentInputObjectTypeResolver(): CustomPostAddCommentInputObjectTypeResolver
    {
        if ($this->customPostAddCommentInputObjectTypeResolver === null) {
            /** @var CustomPostAddCommentInputObjectTypeResolver */
            $customPostAddCommentInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostAddCommentInputObjectTypeResolver::class);
            $this->customPostAddCommentInputObjectTypeResolver = $customPostAddCommentInputObjectTypeResolver;
        }
        return $this->customPostAddCommentInputObjectTypeResolver;
    }
    final public function setCustomPostAddCommentMutationPayloadObjectTypeResolver(CustomPostAddCommentMutationPayloadObjectTypeResolver $customPostAddCommentMutationPayloadObjectTypeResolver): void
    {
        $this->customPostAddCommentMutationPayloadObjectTypeResolver = $customPostAddCommentMutationPayloadObjectTypeResolver;
    }
    final protected function getCustomPostAddCommentMutationPayloadObjectTypeResolver(): CustomPostAddCommentMutationPayloadObjectTypeResolver
    {
        if ($this->customPostAddCommentMutationPayloadObjectTypeResolver === null) {
            /** @var CustomPostAddCommentMutationPayloadObjectTypeResolver */
            $customPostAddCommentMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CustomPostAddCommentMutationPayloadObjectTypeResolver::class);
            $this->customPostAddCommentMutationPayloadObjectTypeResolver = $customPostAddCommentMutationPayloadObjectTypeResolver;
        }
        return $this->customPostAddCommentMutationPayloadObjectTypeResolver;
    }
    final public function setPayloadableAddCommentToCustomPostMutationResolver(PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver): void
    {
        $this->payloadableAddCommentToCustomPostMutationResolver = $payloadableAddCommentToCustomPostMutationResolver;
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
    final public function setPayloadableAddCommentToCustomPostBulkOperationMutationResolver(PayloadableAddCommentToCustomPostBulkOperationMutationResolver $payloadableAddCommentToCustomPostBulkOperationMutationResolver): void
    {
        $this->payloadableAddCommentToCustomPostBulkOperationMutationResolver = $payloadableAddCommentToCustomPostBulkOperationMutationResolver;
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
    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        if ($this->commentTypeAPI === null) {
            /** @var CommentTypeAPIInterface */
            $commentTypeAPI = $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
            $this->commentTypeAPI = $commentTypeAPI;
        }
        return $this->commentTypeAPI;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'addComment',
            'addComments',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addComment' => $this->getAddCommentFieldDescription(),
            'addComments' => $this->__('Add comments to the custom post', 'comment-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getAddCommentFieldDescription(): string
    {
        return $this->__('Add a comment to the custom post', 'comment-mutations');
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        if (!$usePayloadableCommentMutations) {
            return match ($fieldName) {
                'addComment' => SchemaTypeModifiers::NONE,
                'addComments' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addComment' => SchemaTypeModifiers::NON_NULLABLE,
            'addComments' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'addComment' => [
                MutationInputProperties::INPUT => $this->getCustomPostAddCommentInputObjectTypeResolver(),
            ],
            'addComments' => $this->getBulkOperationFieldArgNameTypeResolvers($this->getCustomPostAddCommentInputObjectTypeResolver()),
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['addComment' => MutationInputProperties::INPUT] => SchemaTypeModifiers::MANDATORY,
            ['addComments' => SchemaCommonsMutationInputProperties::INPUTS] => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
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
            'addComment',
            'addComments'
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
        $customPost = $object;
        switch ($field->getName()) {
            case 'addComment':
                /** @var stdClass */
                $input = &$fieldArgsForMutationForObject[MutationInputProperties::INPUT];
                $input->{MutationInputProperties::CUSTOMPOST_ID} = $objectTypeResolver->getID($customPost);
                break;
            case 'addComments':
                /** @var stdClass[] */
                $inputs = $fieldArgsForMutationForObject[SchemaCommonsMutationInputProperties::INPUTS];
                $objectID = $objectTypeResolver->getID($customPost);
                foreach ($inputs as &$input) {
                    $input->{MutationInputProperties::CUSTOMPOST_ID} = $objectID;
                }
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
            'addComment' => $usePayloadableCommentMutations
                ? $this->getPayloadableAddCommentToCustomPostMutationResolver()
                : $this->getAddCommentToCustomPostMutationResolver(),
            'addComments' => $usePayloadableCommentMutations
                ? $this->getPayloadableAddCommentToCustomPostBulkOperationMutationResolver()
                : $this->getAddCommentToCustomPostBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        return match ($fieldName) {
            'addComment',
            'addComments'
                => $usePayloadableCommentMutations
                    ? $this->getCustomPostAddCommentMutationPayloadObjectTypeResolver()
                    : $this->getCommentObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
