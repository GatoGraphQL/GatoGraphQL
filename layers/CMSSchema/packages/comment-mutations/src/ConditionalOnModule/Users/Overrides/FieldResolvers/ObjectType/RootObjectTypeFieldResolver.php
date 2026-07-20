<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType\AddCommentToCustomPostObjectTypeFieldResolverTrait;
use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\RootObjectTypeFieldResolver as UpstreamRootObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RootObjectTypeFieldResolver extends UpstreamRootObjectTypeFieldResolver
{
    use AddCommentToCustomPostObjectTypeFieldResolverTrait;

    private ?UserTypeAPIInterface $userTypeAPI = null;

    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }

    /**
     * Higher priority to override the previous FieldResolver
     */
    public function getPriorityToAttachToClasses(): int
    {
        return parent::getPriorityToAttachToClasses() + 10;
    }

    /**
     * If not provided, set the properties from the logged-in user
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>|null null in case of validation error
     */
    public function prepareFieldArgs(
        array $fieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        if (
            in_array($field->getName(), [
            'addCommentToCustomPosts',
            'replyComments',
            ])
        ) {
            return $this->prepareBulkOperationAddCommentFieldArgs($fieldArgs);
        }

        if (
            in_array($field->getName(), [
            'addCommentToCustomPost',
            'replyComment',
            ])
        ) {
            return $this->prepareAddCommentFieldArgs($fieldArgs);
        }

        /**
         * Any other field (such as updating or deleting a comment) must not
         * have the logged-in user's data injected into its input, as that
         * would override the comment's own author.
         */
        return parent::prepareFieldArgs(
            $fieldArgs,
            $objectTypeResolver,
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }
}
