<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType\AddCommentToCustomPostObjectTypeFieldResolverTrait;
use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
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
            'replyWithComments',
            ])
        ) {
            return $this->prepareBulkOperationAddCommentFieldArgs($fieldArgs);
        }
        return $this->prepareAddCommentFieldArgs($fieldArgs);
    }
}
