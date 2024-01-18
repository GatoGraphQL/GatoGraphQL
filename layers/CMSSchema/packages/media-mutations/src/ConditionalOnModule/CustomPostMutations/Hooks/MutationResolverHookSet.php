<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ConditionalOnModule\CustomPostMutations\Hooks;

use PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostMediaMutations\ObjectModels\MediaItemDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateOrUpdateCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\MediaMutations\ConditionalOnModule\CustomPostMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Constants\HookNames;
use PoPCMSSchema\Media\Constants\InputProperties;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use stdClass;

class MutationResolverHookSet extends AbstractHookSet
{
    use CreateOrUpdateCustomPostMutationResolverTrait;

    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI = null;

    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        if ($this->nameResolver === null) {
            /** @var NameResolverInterface */
            $nameResolver = $this->instanceManager->getInstance(NameResolverInterface::class);
            $this->nameResolver = $nameResolver;
        }
        return $this->nameResolver;
    }
    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        if ($this->userRoleTypeAPI === null) {
            /** @var UserRoleTypeAPIInterface */
            $userRoleTypeAPI = $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
            $this->userRoleTypeAPI = $userRoleTypeAPI;
        }
        return $this->userRoleTypeAPI;
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
    final public function setCustomPostTypeMutationAPI(CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI): void
    {
        $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
    }
    final protected function getCustomPostTypeMutationAPI(): CustomPostTypeMutationAPIInterface
    {
        if ($this->customPostTypeMutationAPI === null) {
            /** @var CustomPostTypeMutationAPIInterface */
            $customPostTypeMutationAPI = $this->instanceManager->getInstance(CustomPostTypeMutationAPIInterface::class);
            $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
        }
        return $this->customPostTypeMutationAPI;
    }

    // private ?CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI = null;
    // private ?MediaTypeAPIInterface $mediaTypeAPI = null;

    // final public function setCustomPostMediaTypeMutationAPI(CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI): void
    // {
    //     $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    // }
    // final protected function getCustomPostMediaTypeMutationAPI(): CustomPostMediaTypeMutationAPIInterface
    // {
    //     if ($this->customPostMediaTypeMutationAPI === null) {
    //         /** @var CustomPostMediaTypeMutationAPIInterface */
    //         $customPostMediaTypeMutationAPI = $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
    //         $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    //     }
    //     return $this->customPostMediaTypeMutationAPI;
    // }
    // final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    // {
    //     $this->mediaTypeAPI = $mediaTypeAPI;
    // }
    // final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    // {
    //     if ($this->mediaTypeAPI === null) {
    //         /** @var MediaTypeAPIInterface */
    //         $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    //         $this->mediaTypeAPI = $mediaTypeAPI;
    //     }
    //     return $this->mediaTypeAPI;
    // }

    protected function init(): void
    {
        App::addAction(
            HookNames::VALIDATE_CREATE_MEDIA_ITEM,
            $this->maybeValidateCustomPost(...),
            10,
            2
        );
        // App::addAction(
        //     HookNames::EXECUTE_CREATE_OR_UPDATE,
        //     $this->maybeSetOrRemoveFeaturedImage(...),
        //     10,
        //     2
        // );
        // App::addFilter(
        //     HookNames::ERROR_PAYLOAD,
        //     $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
        //     10,
        //     2
        // );
    }

    public function maybeValidateCustomPost(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
        if ($customPostID === null) {
            return;
        }

        // Make sure the custom post exists
        $this->validateCustomPostExists(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateCanLoggedInUserEditCustomPosts(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateCanLoggedInUserEditCustomPost(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    // /**
    //  * If entry "featuredImageID" has an ID, set it. If it is null, remove it
    //  */
    // public function maybeSetOrRemoveFeaturedImage(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
    // {
    //     $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
    //     if ($customPostID === null) {
    //         return;
    //     }

    //     /**
    //      * @var stdClass|null
    //      */
    //     $featuredImageBy = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_BY);
    //     if ($featuredImageBy === null) {
    //         /**
    //          * If is `null` or {} => remove the featured image
    //          */
    //         $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
    //         return;
    //     }

    //     $featuredImageID = null;
    //     if (isset($featuredImageBy->{InputProperties::ID})) {
    //         /** @var string|int */
    //         $featuredImageID = $featuredImageBy->{InputProperties::ID};
    //     } elseif (isset($featuredImageBy->{InputProperties::SLUG})) {
    //         $mediaTypeAPI = $this->getMediaTypeAPI();
    //         /** @var string */
    //         $featuredImageSlug = $featuredImageBy->{InputProperties::SLUG};
    //         /** @var object */
    //         $featuredImage = $mediaTypeAPI->getMediaItemBySlug($featuredImageSlug);
    //         $featuredImageID = $mediaTypeAPI->getMediaItemID($featuredImage);
    //     } elseif (
    //         property_exists($featuredImageBy, InputProperties::ID)
    //         || property_exists($featuredImageBy, InputProperties::SLUG)
    //     ) {
    //         /**
    //          * Passing `updatePost(input: { featuredImageBy: {id: null} })`
    //          * or `updatePost(input: { featuredImageBy: {slug: null} })`
    //          * is supported, in which case the featured image is removed
    //          */
    //         $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
    //         return;
    //     }

    //     if ($featuredImageID === null) {
    //         return;
    //     }

    //     /** @var string|int $featuredImageID */
    //     $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $featuredImageID);
    // }

    // public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
    //     ErrorPayloadInterface $errorPayload,
    //     FeedbackItemResolution $feedbackItemResolution
    // ): ErrorPayloadInterface {
    //     return match (
    //         [
    //         $feedbackItemResolution->getFeedbackProviderServiceClass(),
    //         $feedbackItemResolution->getCode()
    //         ]
    //     ) {
    //         [
    //             MutationErrorFeedbackItemProvider::class,
    //             MutationErrorFeedbackItemProvider::E2,
    //         ] => new MediaItemDoesNotExistErrorPayload(
    //             $feedbackItemResolution->getMessage(),
    //         ),
    //         [
    //             MutationErrorFeedbackItemProvider::class,
    //             MutationErrorFeedbackItemProvider::E5,
    //         ] => new MediaItemDoesNotExistErrorPayload(
    //             $feedbackItemResolution->getMessage(),
    //         ),
    //         default => $errorPayload,
    //     };
    // }
}
