<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\TypeAPIs\MediaTypeAPIInterface as UserMediaTypeAPIInterface;
use PoPCMSSchema\Comments\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

/**
 * Override fields from the upstream class, getting the data from the user
 */
class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
{
    private ?UserMediaTypeAPIInterface $userMediaTypeAPI = null;
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserMediaTypeAPI(UserMediaTypeAPIInterface $userMediaTypeAPI): void
    {
        $this->userMediaTypeAPI = $userMediaTypeAPI;
    }
    final protected function getUserMediaTypeAPI(): UserMediaTypeAPIInterface
    {
        if ($this->userMediaTypeAPI === null) {
            /** @var UserMediaTypeAPIInterface */
            $userMediaTypeAPI = $this->instanceManager->getInstance(UserMediaTypeAPIInterface::class);
            $this->userMediaTypeAPI = $userMediaTypeAPI;
        }
        return $this->userMediaTypeAPI;
    }
    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->mustUserBeLoggedInToAddComment();
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'authorName',
            'authorURL',
            'authorEmail',
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $comment = $object;
        $commentUserID = $this->getUserMediaTypeAPI()->getCommentUserID($comment);

        /**
         * Check there is an author. Otherwise, let the upstream resolve it
         */
        if ($commentUserID === null) {
            return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'authorName':
                return $this->getUserTypeAPI()->getUserDisplayName($commentUserID);

            case 'authorURL':
                return $this->getUserTypeAPI()->getUserWebsiteURL($commentUserID);

            case 'authorEmail':
                return $this->getUserTypeAPI()->getUserEmail($commentUserID);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
