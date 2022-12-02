<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostMediaMutations\ObjectModels\MediaItemDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Hooks\AbstractHookSet;

class MutationResolverHookSet extends AbstractHookSet
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
        /** @var CustomPostMediaTypeMutationAPIInterface */
        return $this->customPostMediaTypeMutationAPI ??= $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
    }
    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI ??= $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }

    protected function init(): void
    {
        App::addAction(
            HookNames::VALIDATE_CREATE_OR_UPDATE,
            $this->maybeValidateFeaturedImage(...),
            10,
            2
        );
        App::addAction(
            HookNames::EXECUTE_CREATE_OR_UPDATE,
            $this->maybeSetOrRemoveFeaturedImage(...),
            10,
            2
        );
        App::addFilter(
            HookNames::ERROR_PAYLOAD,
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
        $featuredImageID = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_ID);
        if ($featuredImageID === null) {
            return;
        }
        $this->validateMediaItemExists(
            $featuredImageID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * Entry "featuredImageID" must either have an ID or `null` to execute
     * the mutation. Only if not provided, then nothing to do.
     */
    protected function canExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        return $fieldDataAccessor->hasValue(MutationInputProperties::FEATUREDIMAGE_ID);
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
         * If it has an ID, set the featured image
         *
         * @var string|int|null
         */
        $featuredImageID = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_ID);
        if ($featuredImageID !== null) {
            $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $featuredImageID);
            return;
        }
        /**
         * If is `null` => remove the featured image
         */
        $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        FeedbackItemResolution $feedbackItemResolution
    ): ErrorPayloadInterface {
        return match ([
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode()
        ]) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new MediaItemDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => $errorPayload,
        };
    }
}
