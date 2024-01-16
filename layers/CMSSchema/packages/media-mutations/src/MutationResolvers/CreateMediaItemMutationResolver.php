<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\MediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use PoPCMSSchema\Comments\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoPCMSSchema\MediaMutations\Constants\HookNames;
use stdClass;

/**
 * Add a comment to a custom post. The user may be logged-in or not
 */
class CreateMediaItemMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?MediaTypeMutationAPIInterface $mediaTypeMutationAPI = null;
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?RequestHelperServiceInterface $requestHelperService = null;

    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        if ($this->mediaTypeAPI === null) {
            /** @var MediaTypeAPIInterface */
            $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
            $this->mediaTypeAPI = $mediaTypeAPI;
        }
        return $this->mediaTypeAPI;
    }
    final public function setMediaTypeMutationAPI(MediaTypeMutationAPIInterface $mediaTypeMutationAPI): void
    {
        $this->mediaTypeMutationAPI = $mediaTypeMutationAPI;
    }
    final protected function getMediaTypeMutationAPI(): MediaTypeMutationAPIInterface
    {
        if ($this->mediaTypeMutationAPI === null) {
            /** @var MediaTypeMutationAPIInterface */
            $mediaTypeMutationAPI = $this->instanceManager->getInstance(MediaTypeMutationAPIInterface::class);
            $this->mediaTypeMutationAPI = $mediaTypeMutationAPI;
        }
        return $this->mediaTypeMutationAPI;
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
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }
    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        if ($this->requestHelperService === null) {
            /** @var RequestHelperServiceInterface */
            $requestHelperService = $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
            $this->requestHelperService = $requestHelperService;
        }
        return $this->requestHelperService;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $field = $fieldDataAccessor->getField();

        // Check that the user is logged-in
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
            if ($errorFeedbackItemResolution !== null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $errorFeedbackItemResolution,
                        $field,
                    )
                );
                return;
            }
        } elseif ($moduleConfiguration->requireCommenterNameAndEmail()) {
            // Validate if the commenter's name and email are mandatory
            if (!$fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_NAME)) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E2,
                        ),
                        $field,
                    )
                );
            }
            if (!$fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_EMAIL)) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E3,
                        ),
                        $field,
                    )
                );
            }
        }

        // Either provide the customPostID, or retrieve it from the parent comment
        if (!$fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID) && !$fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E4,
                    ),
                    $field,
                )
            );
        }
        // Make sure the parent comment exists
        // Either provide the customPostID, or retrieve it from the parent comment
        if ($parentCommentID = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID)) {
            $parentComment = $this->getMediaTypeAPI()->getComment($parentCommentID);
            if ($parentComment === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E6,
                            [
                                $parentCommentID,
                            ]
                        ),
                        $field,
                    )
                );
            }
        }
        // Make sure the custom post exists
        if ($customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID)) {
            if (!$this->getCustomPostTypeAPI()->customPostExists($customPostID)) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E7,
                            [
                                $customPostID,
                            ]
                        ),
                        $field,
                    )
                );
            } else {
                // Validate the corresponding CPT supports comments
                /** @var string */
                $customPostType = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
                if (!$this->getMediaTypeAPI()->doesCustomPostTypeSupportComments($customPostType)) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                MutationErrorFeedbackItemProvider::class,
                                MutationErrorFeedbackItemProvider::E8,
                                [
                                    $customPostType,
                                ]
                            ),
                            $field,
                        )
                    );
                } elseif (!$this->getMediaTypeAPI()->areCommentsOpen($customPostID)) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                MutationErrorFeedbackItemProvider::class,
                                MutationErrorFeedbackItemProvider::E9,
                                [
                                    $customPostID
                                ]
                            ),
                            $field,
                        )
                    );
                }
            }
        }
        /** @var stdClass */
        $commentAs = $fieldDataAccessor->getValue(MutationInputProperties::COMMENT_AS);
        /**
         * @todo In addition to "html", support additional oneof properties for the mutation (eg: provide "blocks" for Gutenberg)
         */
        if (!$commentAs->{MutationInputProperties::HTML}) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E5,
                    ),
                    $field,
                )
            );
        }
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function additionals(string|int $comment_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction(HookNames::ADD_COMMENT, $comment_id, $fieldDataAccessor);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCommentData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        /** @var stdClass */
        $commentAs = $fieldDataAccessor->getValue(MutationInputProperties::COMMENT_AS);
        $comment_data = [
            'authorIP' => $this->getRequestHelperService()->getClientIPAddress(),
            'agent' => App::server('HTTP_USER_AGENT'),
            /**
             * @todo In addition to "html", support additional oneof properties for the mutation (eg: provide "blocks" for Gutenberg)
             */
            'content' => $commentAs->{MutationInputProperties::HTML},
            'parent' => $fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID),
            'customPostID' => $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID),
        ];
        /**
         * Override with the user's properties
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            $userID = App::getState('current-user-id');
            $comment_data['userID'] = $userID;
            $comment_data['author'] = $this->getUserTypeAPI()->getUserDisplayName($userID);
            $comment_data['authorEmail'] = $this->getUserTypeAPI()->getUserEmail($userID);
            $comment_data['authorURL'] = $this->getUserTypeAPI()->getUserWebsiteURL($userID);
        } else {
            if ($userID = App::getState('current-user-id')) {
                $comment_data['userID'] = $userID;
            }
            $comment_data['author'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_NAME);
            $comment_data['authorEmail'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_EMAIL);
            $comment_data['authorURL'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_URL);
        }

        // If the parent comment is provided and the custom post is not,
        // then retrieve it from there
        if ($comment_data['parent'] && !$comment_data['customPostID']) {
            /** @var object */
            $parentComment = $this->getMediaTypeAPI()->getComment($comment_data['parent']);
            $comment_data['customPostID'] = $this->getMediaTypeAPI()->getCommentPostID($parentComment);
        }

        return $comment_data;
    }

    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     */
    protected function insertComment(array $comment_data): string|int
    {
        return $this->getMediaTypeMutationAPI()->insertComment($comment_data);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $comment_data = $this->getCommentData($fieldDataAccessor);
        $comment_id = $this->insertComment($comment_data);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($comment_id, $fieldDataAccessor);

        return $comment_id;
    }
}
