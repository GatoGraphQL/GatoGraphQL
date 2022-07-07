<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
{
    use AddCommentToCustomPostObjectTypeFieldResolverTrait;

    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
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
     * @param array<string,mixed> $fieldData
     * @return array<string,mixed>|null null in case of validation error
     */
    public function prepareFieldData(
        array $fieldData,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        return $this->prepareAddCommentFieldData($fieldData);
    }
}
