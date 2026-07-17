<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\CommentMutations\Constants\CommentCRUDHookNames;
use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use stdClass;

abstract class AbstractUpdateCommentMutationResolver extends AbstractEditCommentMutationResolver
{
    use UpdateCommentMutationResolverTrait;

    protected function validateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $commentID = $this->getCommentID($fieldDataAccessor);

        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCommentExists($commentID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int */
        $commentID = $commentID;

        $this->validateCanLoggedInUserEditComment(
            $commentID,
            MutationErrorFeedbackItemProvider::E11,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateContentIsNotEmpty($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        // Allow components to inject their own validations
        App::doAction(
            CommentCRUDHookNames::VALIDATE_UPDATE_COMMENT,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * The content is optional, but when provided it must not be empty.
     */
    protected function validateContentIsNotEmpty(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $content = $this->getContent($fieldDataAccessor);
        if ($content === null || trim($content) !== '') {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E5,
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function getContent(FieldDataAccessorInterface $fieldDataAccessor): ?string
    {
        /** @var stdClass|null */
        $commentAs = $fieldDataAccessor->getValue(MutationInputProperties::COMMENT_AS);
        if ($commentAs === null) {
            return null;
        }
        /** @var string|null */
        $html = $commentAs->{MutationInputProperties::HTML} ?? null;
        return $html;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCommentData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $commentData = [];

        $content = $this->getContent($fieldDataAccessor);
        if ($content !== null) {
            $commentData['content'] = $content;
        }

        foreach (
            [
            MutationInputProperties::AUTHOR_NAME => 'author',
            MutationInputProperties::AUTHOR_EMAIL => 'authorEmail',
            MutationInputProperties::AUTHOR_URL => 'authorURL',
            ] as $inputFieldName => $commentDataKey
        ) {
            if (!$fieldDataAccessor->hasValue($inputFieldName)) {
                continue;
            }
            $commentData[$commentDataKey] = $fieldDataAccessor->getValue($inputFieldName);
        }

        return App::applyFilters(
            CommentCRUDHookNames::GET_UPDATE_COMMENT_DATA,
            $commentData,
            $fieldDataAccessor,
        );
    }

    protected function getCommentStatus(FieldDataAccessorInterface $fieldDataAccessor): ?string
    {
        /** @var string|null */
        return $fieldDataAccessor->getValue(MutationInputProperties::STATUS);
    }

    /**
     * @return string|int The ID of the updated comment
     * @throws CommentCRUDMutationException If there was an error
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $commentID = $this->getCommentID($fieldDataAccessor);

        $commentData = $this->getCommentData($fieldDataAccessor);
        if ($commentData !== []) {
            $this->getCommentTypeMutationAPI()->updateComment(
                $commentID,
                $commentData,
            );
        }

        /**
         * The status is applied via a dedicated API method, as moderating
         * a comment is not the same as editing its data.
         */
        $commentStatus = $this->getCommentStatus($fieldDataAccessor);
        if ($commentStatus !== null) {
            $this->getCommentTypeMutationAPI()->setCommentStatus(
                $commentID,
                $commentStatus,
            );
        }

        App::doAction(
            CommentCRUDHookNames::EXECUTE_UPDATE_COMMENT,
            $commentID,
            $fieldDataAccessor,
        );

        return $commentID;
    }
}
