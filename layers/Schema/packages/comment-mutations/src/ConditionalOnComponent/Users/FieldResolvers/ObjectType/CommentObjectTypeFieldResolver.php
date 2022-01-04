<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\ConditionalOnComponent\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CommentMutations\Component;
use PoPSchema\CommentMutations\ComponentConfiguration;
use PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface as UserCommentTypeAPIInterface;
use PoPSchema\Comments\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

/**
 * Override fields from the upstream class, getting the data from the user
 */
class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
{
    private ?UserCommentTypeAPIInterface $userCommentTypeAPI = null;
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserCommentTypeAPI(UserCommentTypeAPIInterface $userCommentTypeAPI): void
    {
        $this->userCommentTypeAPI = $userCommentTypeAPI;
    }
    final protected function getUserCommentTypeAPI(): UserCommentTypeAPIInterface
    {
        return $this->userCommentTypeAPI ??= $this->instanceManager->getInstance(UserCommentTypeAPIInterface::class);
    }
    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
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
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->mustUserBeLoggedInToAddComment();
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
        array $fieldArgs
    ): bool {
        $comment = $object;
        $commentUserID = $this->getUserCommentTypeAPI()->getCommentUserId($comment);
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $comment = $object;
        $commentUserID = $this->getUserCommentTypeAPI()->getCommentUserId($comment);
        switch ($fieldName) {
            case 'authorName':
                return $this->getUserTypeAPI()->getUserDisplayName($commentUserID);

            case 'authorURL':
                return $this->getUserTypeAPI()->getUserWebsiteURL($commentUserID);

            case 'authorEmail':
                return $this->getUserTypeAPI()->getUserEmail($commentUserID);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
