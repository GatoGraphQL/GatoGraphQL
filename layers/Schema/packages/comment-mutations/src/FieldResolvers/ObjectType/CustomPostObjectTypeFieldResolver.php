<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CommentMutations\TypeResolvers\InputObjectType\CustomPostAddCommentFilterInputObjectTypeResolver;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class CustomPostObjectTypeFieldResolver extends AbstractAddCommentToCustomPostObjectTypeFieldResolver
{
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?CustomPostAddCommentFilterInputObjectTypeResolver $customPostAddCommentFilterInputObjectTypeResolver = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setAddCommentToCustomPostMutationResolver(AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver): void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
    }
    final protected function getAddCommentToCustomPostMutationResolver(): AddCommentToCustomPostMutationResolver
    {
        return $this->addCommentToCustomPostMutationResolver ??= $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
    }
    final public function setCustomPostAddCommentFilterInputObjectTypeResolver(CustomPostAddCommentFilterInputObjectTypeResolver $customPostAddCommentFilterInputObjectTypeResolver): void
    {
        $this->customPostAddCommentFilterInputObjectTypeResolver = $customPostAddCommentFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostAddCommentFilterInputObjectTypeResolver(): CustomPostAddCommentFilterInputObjectTypeResolver
    {
        return $this->customPostAddCommentFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostAddCommentFilterInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'addComment',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addComment' => $this->__('Add a comment to the custom post', 'comment-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'addComment' => [
                'input' => $this->getCustomPostAddCommentFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['addComment' => 'input'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnObject(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return match ($fieldName) {
            'addComment' => true,
            default => parent::validateMutationOnObject($objectTypeResolver, $fieldName),
        };
    }

    protected function getMutationFieldArgsForObject(
        array $mutationFieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName
    ): array {
        $mutationFieldArgs = parent::getMutationFieldArgsForObject(
            $mutationFieldArgs,
            $objectTypeResolver,
            $object,
            $fieldName
        );
        $customPost = $object;
        switch ($fieldName) {
            case 'addComment':
                $mutationFieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $objectTypeResolver->getID($customPost);
                break;
        }

        return $mutationFieldArgs;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'addComment' => $this->getAddCommentToCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'addComment' => $this->getCommentObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
