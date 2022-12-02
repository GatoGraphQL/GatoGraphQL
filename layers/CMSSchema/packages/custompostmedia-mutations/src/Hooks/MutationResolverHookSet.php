<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
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
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_VALIDATE_CREATE_OR_UPDATE,
            $this->maybeValidateFeaturedImage(...),
            10,
            2
        );
        App::addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            $this->setOrRemoveFeaturedImage(...),
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
    public function setOrRemoveFeaturedImage(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
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
}
