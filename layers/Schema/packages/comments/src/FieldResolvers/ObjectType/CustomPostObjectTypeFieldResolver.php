<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Comments\FieldResolvers\InterfaceType\CommentableInterfaceTypeFieldResolver;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected CommentTypeAPIInterface $commentTypeAPI;
    protected CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver;

    #[Required]
    public function autowireCustomPostObjectTypeFieldResolver(
        CommentTypeAPIInterface $commentTypeAPI,
        CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver,
    ): void {
        $this->commentTypeAPI = $commentTypeAPI;
        $this->commentableInterfaceTypeFieldResolver = $commentableInterfaceTypeFieldResolver;
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
            $this->commentableInterfaceTypeFieldResolver,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'commentCountForAdmin',
            'comments',
            'commentsForAdmin',
        ];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            case 'areCommentsOpen':
                return $this->commentTypeAPI->areCommentsOpen($objectTypeResolver->getID($post));

            case 'hasComments':
                return $objectTypeResolver->resolveValue($post, 'commentCount', $variables, $expressions, $options) > 0;
        }

        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            [
                'customPostID' => $objectTypeResolver->getID($post),
            ]
        );
        switch ($fieldName) {
            case 'commentCount':
            case 'commentCountForAdmin':
                return $this->commentTypeAPI->getCommentCount($query);

            case 'comments':
            case 'commentsForAdmin':
                return $this->commentTypeAPI->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
