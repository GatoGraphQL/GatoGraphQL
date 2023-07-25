<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\Media\Constants\InputProperties;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

class SetFeaturedImageOnCustomPostMutationResolver extends AbstractSetOrRemoveFeaturedImageOnCustomPostMutationResolver
{
    use SetFeaturedImageOnCustomPostMutationResolverTrait;

    private ?CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI = null;
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;

    final public function setCustomPostMediaTypeMutationAPI(CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI): void
    {
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    }
    final protected function getCustomPostMediaTypeMutationAPI(): CustomPostMediaTypeMutationAPIInterface
    {
        if ($this->customPostMediaTypeMutationAPI === null) {
            /** @var CustomPostMediaTypeMutationAPIInterface */
            $customPostMediaTypeMutationAPI = $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
            $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
        }
        return $this->customPostMediaTypeMutationAPI;
    }
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

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $mediaItemID = null;
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
        $mediaItemBy = $fieldDataAccessor->getValue(MutationInputProperties::MEDIAITEM_BY);
        if (isset($mediaItemBy->{InputProperties::ID})) {
            /** @var string|int */
            $mediaItemID = $mediaItemBy->{InputProperties::ID};
        } elseif (isset($mediaItemBy->{InputProperties::SLUG})) {
            $mediaTypeAPI = $this->getMediaTypeAPI();
            /** @var string */
            $mediaItemSlug = $mediaItemBy->{InputProperties::SLUG};
            /** @var object */
            $mediaItem = $mediaTypeAPI->getMediaItemBySlug($mediaItemSlug);
            $mediaItemID = $mediaTypeAPI->getMediaItemID($mediaItem);
        }
        /** @var string|int $mediaItemID */
        $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $mediaItemID);
        return $customPostID;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validate(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $mediaItemBy = $fieldDataAccessor->getValue(MutationInputProperties::MEDIAITEM_BY);
        if ($mediaItemBy === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E1,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        if (isset($mediaItemBy->{InputProperties::ID})) {
            /** @var string|int */
            $mediaItemID = $mediaItemBy->{InputProperties::ID};
            $this->validateMediaItemByIDExists(
                $mediaItemID,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } elseif (isset($mediaItemBy->{InputProperties::SLUG})) {
            /** @var string */
            $mediaItemSlug = $mediaItemBy->{InputProperties::SLUG};
            $this->validateMediaItemBySlugExists(
                $mediaItemSlug,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }
}
