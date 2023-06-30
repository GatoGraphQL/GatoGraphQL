<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
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
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
        $mediaItemID = $fieldDataAccessor->getValue(MutationInputProperties::MEDIA_ITEM_ID);
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

        $mediaItemID = $fieldDataAccessor->getValue(MutationInputProperties::MEDIA_ITEM_ID);
        $this->validateMediaItemExists(
            $mediaItemID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }
}
