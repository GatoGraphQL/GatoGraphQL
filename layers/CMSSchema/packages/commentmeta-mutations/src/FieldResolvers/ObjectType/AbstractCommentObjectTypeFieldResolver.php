<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\Module;
use PoPCMSSchema\CommentMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\AddCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\DeleteCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableAddCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableDeleteCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableSetCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableUpdateCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\SetCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\MutationResolvers\UpdateCommentMetaMutationResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\CommentAddMetaInputObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\CommentDeleteMetaInputObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\CommentSetMetaInputObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\InputObjectType\CommentUpdateMetaInputObjectTypeResolver;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractCommentObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?CommentAddMetaInputObjectTypeResolver $commentAddMetaInputObjectTypeResolver = null;
    private ?CommentDeleteMetaInputObjectTypeResolver $commentDeleteMetaInputObjectTypeResolver = null;
    private ?CommentSetMetaInputObjectTypeResolver $commentSetMetaInputObjectTypeResolver = null;
    private ?CommentUpdateMetaInputObjectTypeResolver $commentUpdateMetaInputObjectTypeResolver = null;
    private ?AddCommentMetaMutationResolver $addCommentMetaMutationResolver = null;
    private ?DeleteCommentMetaMutationResolver $deleteCommentMetaMutationResolver = null;
    private ?SetCommentMetaMutationResolver $setCommentMetaMutationResolver = null;
    private ?UpdateCommentMetaMutationResolver $updateCommentMetaMutationResolver = null;
    private ?PayloadableDeleteCommentMetaMutationResolver $payloadableDeleteCommentMetaMutationResolver = null;
    private ?PayloadableSetCommentMetaMutationResolver $payloadableSetCommentMetaMutationResolver = null;
    private ?PayloadableUpdateCommentMetaMutationResolver $payloadableUpdateCommentMetaMutationResolver = null;
    private ?PayloadableAddCommentMetaMutationResolver $payloadableAddCommentMetaMutationResolver = null;

    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }
    final protected function getCommentAddMetaInputObjectTypeResolver(): CommentAddMetaInputObjectTypeResolver
    {
        if ($this->commentAddMetaInputObjectTypeResolver === null) {
            /** @var CommentAddMetaInputObjectTypeResolver */
            $commentAddMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CommentAddMetaInputObjectTypeResolver::class);
            $this->commentAddMetaInputObjectTypeResolver = $commentAddMetaInputObjectTypeResolver;
        }
        return $this->commentAddMetaInputObjectTypeResolver;
    }
    final protected function getCommentDeleteMetaInputObjectTypeResolver(): CommentDeleteMetaInputObjectTypeResolver
    {
        if ($this->commentDeleteMetaInputObjectTypeResolver === null) {
            /** @var CommentDeleteMetaInputObjectTypeResolver */
            $commentDeleteMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CommentDeleteMetaInputObjectTypeResolver::class);
            $this->commentDeleteMetaInputObjectTypeResolver = $commentDeleteMetaInputObjectTypeResolver;
        }
        return $this->commentDeleteMetaInputObjectTypeResolver;
    }
    final protected function getCommentSetMetaInputObjectTypeResolver(): CommentSetMetaInputObjectTypeResolver
    {
        if ($this->commentSetMetaInputObjectTypeResolver === null) {
            /** @var CommentSetMetaInputObjectTypeResolver */
            $commentSetMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CommentSetMetaInputObjectTypeResolver::class);
            $this->commentSetMetaInputObjectTypeResolver = $commentSetMetaInputObjectTypeResolver;
        }
        return $this->commentSetMetaInputObjectTypeResolver;
    }
    final protected function getCommentUpdateMetaInputObjectTypeResolver(): CommentUpdateMetaInputObjectTypeResolver
    {
        if ($this->commentUpdateMetaInputObjectTypeResolver === null) {
            /** @var CommentUpdateMetaInputObjectTypeResolver */
            $commentUpdateMetaInputObjectTypeResolver = $this->instanceManager->getInstance(CommentUpdateMetaInputObjectTypeResolver::class);
            $this->commentUpdateMetaInputObjectTypeResolver = $commentUpdateMetaInputObjectTypeResolver;
        }
        return $this->commentUpdateMetaInputObjectTypeResolver;
    }
    final protected function getAddCommentMetaMutationResolver(): AddCommentMetaMutationResolver
    {
        if ($this->addCommentMetaMutationResolver === null) {
            /** @var AddCommentMetaMutationResolver */
            $addCommentMetaMutationResolver = $this->instanceManager->getInstance(AddCommentMetaMutationResolver::class);
            $this->addCommentMetaMutationResolver = $addCommentMetaMutationResolver;
        }
        return $this->addCommentMetaMutationResolver;
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
    final protected function getSetCommentMetaMutationResolver(): SetCommentMetaMutationResolver
    {
        if ($this->setCommentMetaMutationResolver === null) {
            /** @var SetCommentMetaMutationResolver */
            $setCommentMetaMutationResolver = $this->instanceManager->getInstance(SetCommentMetaMutationResolver::class);
            $this->setCommentMetaMutationResolver = $setCommentMetaMutationResolver;
        }
        return $this->setCommentMetaMutationResolver;
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
    final protected function getPayloadableDeleteCommentMetaMutationResolver(): PayloadableDeleteCommentMetaMutationResolver
    {
        if ($this->payloadableDeleteCommentMetaMutationResolver === null) {
            /** @var PayloadableDeleteCommentMetaMutationResolver */
            $payloadableDeleteCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentMetaMutationResolver::class);
            $this->payloadableDeleteCommentMetaMutationResolver = $payloadableDeleteCommentMetaMutationResolver;
        }
        return $this->payloadableDeleteCommentMetaMutationResolver;
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
    final protected function getPayloadableUpdateCommentMetaMutationResolver(): PayloadableUpdateCommentMetaMutationResolver
    {
        if ($this->payloadableUpdateCommentMetaMutationResolver === null) {
            /** @var PayloadableUpdateCommentMetaMutationResolver */
            $payloadableUpdateCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentMetaMutationResolver::class);
            $this->payloadableUpdateCommentMetaMutationResolver = $payloadableUpdateCommentMetaMutationResolver;
        }
        return $this->payloadableUpdateCommentMetaMutationResolver;
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

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'addMeta',
            'deleteMeta',
            'setMeta',
            'updateMeta',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addMeta' => $this->__('Add a comment meta entry', 'commentmeta-mutations'),
            'deleteMeta' => $this->__('Delete a comment meta entry', 'commentmeta-mutations'),
            'setMeta' => $this->__('Set meta entries to a comment', 'commentmeta-mutations'),
            'updateMeta' => $this->__('Update a comment meta entry', 'commentmeta-mutations'),
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
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => SchemaTypeModifiers::NONE,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta',
            'deleteMeta',
            'setMeta',
            'updateMeta'
                => SchemaTypeModifiers::NON_NULLABLE,
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
            'addMeta' => [
                'input' => $this->getCommentAddMetaInputObjectTypeResolver(),
            ],
            'deleteMeta' => [
                'input' => $this->getCommentDeleteMetaInputObjectTypeResolver(),
            ],
            'setMeta' => [
                'input' => $this->getCommentSetMetaInputObjectTypeResolver(),
            ],
            'updateMeta' => [
                'input' => $this->getCommentUpdateMetaInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['addMeta' => 'input'],
            ['deleteMeta' => 'input'],
            ['setMeta' => 'input'],
            ['updateMeta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                return true;
        }
        return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
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
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $fieldArgsForMutationForObject['input']->{MutationInputProperties::ID} = $objectTypeResolver->getID($comment);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        return match ($fieldName) {
            'addMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableAddCommentMetaMutationResolver()
                : $this->getAddCommentMetaMutationResolver(),
            'updateMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableUpdateCommentMetaMutationResolver()
                : $this->getUpdateCommentMetaMutationResolver(),
            'deleteMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableDeleteCommentMetaMutationResolver()
                : $this->getDeleteCommentMetaMutationResolver(),
            'setMeta' => $usePayloadableCommentMetaMutations
                ? $this->getPayloadableSetCommentMetaMutationResolver()
                : $this->getSetCommentMetaMutationResolver(),
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
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
