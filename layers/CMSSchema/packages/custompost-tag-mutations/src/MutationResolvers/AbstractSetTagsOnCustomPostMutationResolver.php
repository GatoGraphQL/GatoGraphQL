<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

abstract class AbstractSetTagsOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
        $postTags = $fieldDataAccessor->getValue(MutationInputProperties::TAGS);
        $append = $fieldDataAccessor->getValue(MutationInputProperties::APPEND);
        $customPostTagTypeAPI = $this->getCustomPostTagTypeMutationAPI();
        $customPostTagTypeAPI->setTags($customPostID, $postTags, $append);
        return $customPostID;
    }

    abstract protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface;

    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Check that the user is logged-in
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $errorFeedbackItemResolution,
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        if (!$fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E1,
                        [
                            $this->getEntityName(),
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'custompost-tag-mutations');
    }
}
