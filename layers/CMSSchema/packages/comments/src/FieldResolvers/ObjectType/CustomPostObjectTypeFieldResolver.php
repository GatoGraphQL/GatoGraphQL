<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoPCMSSchema\Comments\FieldResolvers\InterfaceType\CommentableInterfaceTypeFieldResolver;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class CustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver = null;

    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setCommentableInterfaceTypeFieldResolver(CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver): void
    {
        $this->commentableInterfaceTypeFieldResolver = $commentableInterfaceTypeFieldResolver;
    }
    final protected function getCommentableInterfaceTypeFieldResolver(): CommentableInterfaceTypeFieldResolver
    {
        return $this->commentableInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(CommentableInterfaceTypeFieldResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getCommentableInterfaceTypeFieldResolver(),
        ];
    }

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
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $post = $object;
        switch ($field->getName()) {
            case 'areCommentsOpen':
                return $this->getCommentTypeAPI()->areCommentsOpen($objectTypeResolver->getID($post));

            case 'hasComments':
                return $objectTypeResolver->resolveValue(
                    $post,
                    new LeafField(
                        'commentCount',
                        null,
                        [],
                        [],
                        $field->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                ) > 0;
        }

        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $field),
            [
                'customPostID' => $objectTypeResolver->getID($post),
            ]
        );
        switch ($field->getName()) {
            case 'commentCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);

            case 'comments':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        }

        return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
    }
}
