<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\ConditionalOnComponent\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CommentMutations\ComponentConfiguration;
use PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface as UserCommentTypeAPIInterface;
use PoPSchema\Comments\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Override fields from the upstream class, getting the data from the user
 */
class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
{
    protected UserCommentTypeAPIInterface $userCommentTypeAPI;
    protected UserTypeAPIInterface $userTypeAPI;

    #[Required]
    public function autowireCommentMutationsCommentObjectTypeFieldResolver(
        UserCommentTypeAPIInterface $userCommentTypeAPI,
        UserTypeAPIInterface $userTypeAPI,
    ): void {
        $this->userCommentTypeAPI = $userCommentTypeAPI;
        $this->userTypeAPI = $userTypeAPI;
    }

    /**
     * Execute before the upstream class
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 20;
    }

    /**
     * Only use it when `mustUserBeLoggedInToAddComment`.
     * Check on runtime (not via container) since this option can be changed in WP.
     */
    public function isServiceEnabled(): bool
    {
        return ComponentConfiguration::mustUserBeLoggedInToAddComment();
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'authorName',
            'authorURL',
            'authorEmail',
        ];
    }

    /**
     * Check there is an author. Otherwise, let the upstream resolve it
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $comment = $object;
        $commentUserID = $this->userCommentTypeAPI->getCommentUserId($comment);
        return $commentUserID !== null;
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
        $comment = $object;
        $commentUserID = $this->userCommentTypeAPI->getCommentUserId($comment);
        switch ($fieldName) {
            case 'authorName':
                return $this->userTypeAPI->getUserDisplayName($commentUserID);

            case 'authorURL':
                return $this->userTypeAPI->getUserWebsiteURL($commentUserID);

            case 'authorEmail':
                return $this->userTypeAPI->getUserEmail($commentUserID);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
