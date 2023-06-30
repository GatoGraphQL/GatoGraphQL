<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
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
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
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
    final public function setCustomPostCommentsFilterInputObjectTypeResolver(CustomPostCommentsFilterInputObjectTypeResolver $customPostCommentsFilterInputObjectTypeResolver): void
    {
        $this->customPostCommentsFilterInputObjectTypeResolver = $customPostCommentsFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostCommentsFilterInputObjectTypeResolver(): CustomPostCommentsFilterInputObjectTypeResolver
    {
        if ($this->customPostCommentsFilterInputObjectTypeResolver === null) {
            /** @var CustomPostCommentsFilterInputObjectTypeResolver */
            $customPostCommentsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostCommentsFilterInputObjectTypeResolver::class);
            $this->customPostCommentsFilterInputObjectTypeResolver = $customPostCommentsFilterInputObjectTypeResolver;
        }
        return $this->customPostCommentsFilterInputObjectTypeResolver;
    }
    final public function setCustomPostCommentPaginationInputObjectTypeResolver(CustomPostCommentPaginationInputObjectTypeResolver $customPostCommentPaginationInputObjectTypeResolver): void
    {
        $this->customPostCommentPaginationInputObjectTypeResolver = $customPostCommentPaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostCommentPaginationInputObjectTypeResolver(): CustomPostCommentPaginationInputObjectTypeResolver
    {
        if ($this->customPostCommentPaginationInputObjectTypeResolver === null) {
            /** @var CustomPostCommentPaginationInputObjectTypeResolver */
            $customPostCommentPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostCommentPaginationInputObjectTypeResolver::class);
            $this->customPostCommentPaginationInputObjectTypeResolver = $customPostCommentPaginationInputObjectTypeResolver;
        }
        return $this->customPostCommentPaginationInputObjectTypeResolver;
    }
    final public function setCommentSortInputObjectTypeResolver(CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver): void
    {
        $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
    }
    final protected function getCommentSortInputObjectTypeResolver(): CommentSortInputObjectTypeResolver
    {
        if ($this->commentSortInputObjectTypeResolver === null) {
            /** @var CommentSortInputObjectTypeResolver */
            $commentSortInputObjectTypeResolver = $this->instanceManager->getInstance(CommentSortInputObjectTypeResolver::class);
            $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
        }
        return $this->commentSortInputObjectTypeResolver;
    }

    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentableInterfaceTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
