<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\Constants\HookNames;
use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MediaMutations\LooseContracts\LooseContractSet;
use PoPCMSSchema\MediaMutations\MutationResolvers\MediaItemCRUDMutationResolverTrait;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use PoPCMSSchema\Media\Constants\InputProperties;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use stdClass;

abstract class AbstractCreateOrUpdateMediaItemMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;
    use MediaItemCRUDMutationResolverTrait;

    private ?MediaTypeMutationAPIInterface $mediaTypeMutationAPI = null;
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?NameResolverInterface $nameResolver = null;
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;

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

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $field = $fieldDataAccessor->getField();

        if ($this->addMediaItemInputField()) {
            // If updating a media item, check that it exists
            /** @var string|int */
            $mediaItemID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            $this->validateMediaItemByIDExists(
                $mediaItemID,
                InputProperties::ID,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        /** @var int|string|null */
        $authorID = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_ID);

        // Check that the user is logged-in
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

        if ($authorID !== null) {
            // If providing the author, check that the user exists
            if ($this->getUserTypeAPI()->getUserByID($authorID) === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E5,
                            [
                                $authorID,
                            ]
                        ),
                        $field,
                    )
                );
            }
        }

        if ($this->canUploadAttachment()) {
            // Validate the user has the needed capability to upload files
            $currentUserID = App::getState('current-user-id');
            $uploadFilesCapability = $this->getNameResolver()->getName(LooseContractSet::NAME_UPLOAD_FILES_CAPABILITY);
            if (
                !$this->getUserRoleTypeAPI()->userCan(
                    $currentUserID,
                    $uploadFilesCapability
                )
            ) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E2,
                        ),
                        $fieldDataAccessor->getField(),
                    )
                );
            }
            elseif ($authorID !== null && $authorID !== $currentUserID) {
                // Validate the logged-in user has the capability to upload files for other people
                $uploadFilesForOtherUsersCapability = $this->getNameResolver()->getName(LooseContractSet::NAME_UPLOAD_FILES_FOR_OTHER_USERS_CAPABILITY);
                if (
                    !$this->getUserRoleTypeAPI()->userCan(
                        $currentUserID,
                        $uploadFilesForOtherUsersCapability
                    )
                ) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                MutationErrorFeedbackItemProvider::class,
                                MutationErrorFeedbackItemProvider::E4,
                            ),
                            $fieldDataAccessor->getField(),
                        )
                    );
                }
            }

            // If providing an existing media item, check that it exists
            /** @var stdClass */
            $from = $fieldDataAccessor->getValue(MutationInputProperties::FROM);

            if (isset($from->{MutationInputProperties::MEDIAITEM_BY})) {
                /** @var stdClass */
                $mediaItemBy = $from->{MutationInputProperties::MEDIAITEM_BY};
                if (isset($mediaItemBy->{InputProperties::ID})) {
                    /** @var string|int */
                    $mediaItemID = $mediaItemBy->{InputProperties::ID};
                    $this->validateMediaItemByIDExists(
                        $mediaItemID,
                        MutationInputProperties::FROM,
                        $fieldDataAccessor,
                        $objectTypeFieldResolutionFeedbackStore,
                    );
                } elseif (isset($mediaItemBy->{InputProperties::SLUG})) {
                    /** @var string */
                    $mediaItemSlug = $mediaItemBy->{InputProperties::SLUG};
                    $this->validateMediaItemBySlugExists(
                        $mediaItemSlug,
                        MutationInputProperties::FROM,
                        $fieldDataAccessor,
                        $objectTypeFieldResolutionFeedbackStore,
                    );
                }
            }
        }

        // Allow components to inject their own validations
        App::doAction(
            HookNames::VALIDATE_CREATE_OR_UPDATE_MEDIA_ITEM,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function addMediaItemInputField(): bool;

    abstract protected function canUploadAttachment(): bool;

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function additionals(string|int $mediaItemID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction(HookNames::CREATE_OR_UPDATE_MEDIA_ITEM, $mediaItemID, $fieldDataAccessor);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMediaItemData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $mediaItemData = [
            'authorID' => $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_ID),
            'title' => $fieldDataAccessor->getValue(MutationInputProperties::TITLE),
            'slug' => $fieldDataAccessor->getValue(MutationInputProperties::SLUG),
            'caption' => $fieldDataAccessor->getValue(MutationInputProperties::CAPTION),
            'description' => $fieldDataAccessor->getValue(MutationInputProperties::DESCRIPTION),
            'altText' => $fieldDataAccessor->getValue(MutationInputProperties::ALT_TEXT),
            'mimeType' => $fieldDataAccessor->getValue(MutationInputProperties::MIME_TYPE),
        ];

        if ($this->addMediaItemInputField()) {
            $mediaItemData['id'] = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        }

        // Inject custom post ID, etc
        $mediaItemData = App::applyFilters(HookNames::GET_CREATE_OR_UPDATE_MEDIA_ITEM_DATA, $mediaItemData, $fieldDataAccessor);

        return $mediaItemData;
    }
}
