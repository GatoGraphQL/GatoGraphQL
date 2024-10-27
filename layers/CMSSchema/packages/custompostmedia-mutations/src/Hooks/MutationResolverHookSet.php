<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\Constants\CustomPostCRUDHookNames;
use PoPCMSSchema\MediaMutations\MutationResolvers\MediaItemCRUDMutationResolverTrait;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use PoPCMSSchema\Media\Constants\InputProperties;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use stdClass;

class MutationResolverHookSet extends AbstractHookSet
{
    use MediaItemCRUDMutationResolverTrait;

    private ?CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI = null;
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?MediaTypeMutationAPIInterface $mediaTypeMutationAPI = null;

    final protected function getCustomPostMediaTypeMutationAPI(): CustomPostMediaTypeMutationAPIInterface
    {
        if ($this->customPostMediaTypeMutationAPI === null) {
            /** @var CustomPostMediaTypeMutationAPIInterface */
            $customPostMediaTypeMutationAPI = $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
            $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
        }
        return $this->customPostMediaTypeMutationAPI;
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
    final protected function getMediaTypeMutationAPI(): MediaTypeMutationAPIInterface
    {
        if ($this->mediaTypeMutationAPI === null) {
            /** @var MediaTypeMutationAPIInterface */
            $mediaTypeMutationAPI = $this->instanceManager->getInstance(MediaTypeMutationAPIInterface::class);
            $this->mediaTypeMutationAPI = $mediaTypeMutationAPI;
        }
        return $this->mediaTypeMutationAPI;
    }

    protected function init(): void
    {
        App::addAction(
            CustomPostCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
            $this->maybeValidateFeaturedImage(...),
            10,
            2
        );
        App::addAction(
            CustomPostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE,
            $this->maybeSetOrRemoveFeaturedImage(...),
            10,
            2
        );
        App::addFilter(
            CustomPostCRUDHookNames::ERROR_PAYLOAD,
            $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
            10,
            2
        );
    }

    public function maybeValidateFeaturedImage(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }
        /** @var stdClass|null */
        $featuredImageBy = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_BY);
        if ($featuredImageBy === null) {
            return;
        }
        if (isset($featuredImageBy->{InputProperties::ID})) {
            /** @var string|int */
            $featuredImageID = $featuredImageBy->{InputProperties::ID};
            $this->validateMediaItemByIDExists(
                $featuredImageID,
                MutationInputProperties::FEATUREDIMAGE_BY,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } elseif (isset($featuredImageBy->{InputProperties::SLUG})) {
            /** @var string */
            $featuredImageSlug = $featuredImageBy->{InputProperties::SLUG};
            $this->validateMediaItemBySlugExists(
                $featuredImageSlug,
                MutationInputProperties::FEATUREDIMAGE_BY,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    /**
     * Entry "featuredImageBy" must either have the input with the
     * ID or slug, or it must have `null` to execute the mutation.
     * (i.e. remove the featured image).
     * Only if not provided, then nothing to do.
     */
    protected function canExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        return $fieldDataAccessor->hasValue(MutationInputProperties::FEATUREDIMAGE_BY);
    }

    /**
     * If entry "featuredImageID" has an ID, set it. If it is null, remove it
     */
    public function maybeSetOrRemoveFeaturedImage(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }

        /**
         * @var stdClass|null
         */
        $featuredImageBy = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_BY);
        if ($featuredImageBy === null) {
            /**
             * If is `null` or {} => remove the featured image
             */
            $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
            return;
        }

        $featuredImageID = null;
        if (isset($featuredImageBy->{InputProperties::ID})) {
            /** @var string|int */
            $featuredImageID = $featuredImageBy->{InputProperties::ID};
        } elseif (isset($featuredImageBy->{InputProperties::SLUG})) {
            $mediaTypeAPI = $this->getMediaTypeAPI();
            /** @var string */
            $featuredImageSlug = $featuredImageBy->{InputProperties::SLUG};
            /** @var object */
            $featuredImage = $mediaTypeAPI->getMediaItemBySlug($featuredImageSlug);
            $featuredImageID = $mediaTypeAPI->getMediaItemID($featuredImage);
        } elseif (
            property_exists($featuredImageBy, InputProperties::ID)
            || property_exists($featuredImageBy, InputProperties::SLUG)
        ) {
            /**
             * Passing `updatePost(input: { featuredImageBy: {id: null} })`
             * or `updatePost(input: { featuredImageBy: {slug: null} })`
             * is supported, in which case the featured image is removed
             */
            $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
            return;
        }

        if ($featuredImageID === null) {
            return;
        }

        /** @var string|int $featuredImageID */
        $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $featuredImageID);
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
    ): ErrorPayloadInterface {
        return $this->createOrUpdateMediaItemErrorPayloadFromObjectTypeFieldResolutionFeedback(
            $objectTypeFieldResolutionFeedback,
        ) ?? $errorPayload;
    }
}
