<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentPaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InterfaceType\CommentableInterfaceTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class CommentableInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?CustomPostCommentsFilterInputObjectTypeResolver $customPostCommentsFilterInputObjectTypeResolver = null;
    private ?CustomPostCommentPaginationInputObjectTypeResolver $customPostCommentPaginationInputObjectTypeResolver = null;
    private ?CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setCustomPostCommentsFilterInputObjectTypeResolver(CustomPostCommentsFilterInputObjectTypeResolver $customPostCommentsFilterInputObjectTypeResolver): void
    {
        $this->customPostCommentsFilterInputObjectTypeResolver = $customPostCommentsFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostCommentsFilterInputObjectTypeResolver(): CustomPostCommentsFilterInputObjectTypeResolver
    {
        return $this->customPostCommentsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostCommentsFilterInputObjectTypeResolver::class);
    }
    final public function setCustomPostCommentPaginationInputObjectTypeResolver(CustomPostCommentPaginationInputObjectTypeResolver $customPostCommentPaginationInputObjectTypeResolver): void
    {
        $this->customPostCommentPaginationInputObjectTypeResolver = $customPostCommentPaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostCommentPaginationInputObjectTypeResolver(): CustomPostCommentPaginationInputObjectTypeResolver
    {
        return $this->customPostCommentPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostCommentPaginationInputObjectTypeResolver::class);
    }
    final public function setCommentSortInputObjectTypeResolver(CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver): void
    {
        $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
    }
    final protected function getCommentSortInputObjectTypeResolver(): CommentSortInputObjectTypeResolver
    {
        return $this->commentSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(CommentSortInputObjectTypeResolver::class);
    }

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentableInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'comments',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'comments' => $this->getCommentObjectTypeResolver(),
            'areCommentsOpen' => $this->getBooleanScalarTypeResolver(),
            'hasComments' => $this->getBooleanScalarTypeResolver(),
            'commentCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'areCommentsOpen',
            'hasComments',
            'commentCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'areCommentsOpen' => $this->__('Are comments open to be added to the custom post', 'pop-comments'),
            'hasComments' => $this->__('Does the custom post have comments?', 'pop-comments'),
            'commentCount' => $this->__('Number of comments added to the custom post', 'pop-comments'),
            'comments' => $this->__('Comments added to the custom post', 'pop-comments'),
            default => parent::getFieldDescription($fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($fieldName);
        return match ($fieldName) {
            'comments' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostCommentsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getCustomPostCommentPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCommentSortInputObjectTypeResolver(),
                ]
            ),
            'commentCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostCommentsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }
}
