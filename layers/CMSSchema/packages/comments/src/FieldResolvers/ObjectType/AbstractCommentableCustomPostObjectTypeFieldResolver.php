<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoPCMSSchema\Comments\FieldResolvers\InterfaceType\CommentableInterfaceTypeFieldResolver;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

abstract class AbstractCommentableCustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use MaybeCommentableCustomPostObjectTypeFieldResolverTrait;

    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver = null;

    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        /** @var CommentTypeAPIInterface */
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setCommentableInterfaceTypeFieldResolver(CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver): void
    {
        $this->commentableInterfaceTypeFieldResolver = $commentableInterfaceTypeFieldResolver;
    }
    final protected function getCommentableInterfaceTypeFieldResolver(): CommentableInterfaceTypeFieldResolver
    {
        /** @var CommentableInterfaceTypeFieldResolver */
        return $this->commentableInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(CommentableInterfaceTypeFieldResolver::class);
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getCommentableInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'comments',
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'areCommentsOpen':
                return $this->getCommentTypeAPI()->areCommentsOpen($customPost);

            case 'hasComments':
                return $objectTypeResolver->resolveValue(
                    $customPost,
                    new LeafField(
                        'commentCount',
                        null,
                        [],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                ) > 0;
        }

        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            [
                'customPostID' => $objectTypeResolver->getID($customPost),
            ]
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'commentCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);

            case 'comments':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
