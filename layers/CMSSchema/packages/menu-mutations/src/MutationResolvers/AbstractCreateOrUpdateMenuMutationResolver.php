<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use DateTime;
use PoPCMSSchema\MenuMutations\Constants\MenuCRUDHookNames;
use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MenuMutations\LooseContracts\LooseContractSet;
use PoPCMSSchema\MenuMutations\MutationResolvers\MenuCRUDMutationResolverTrait;
use PoPCMSSchema\MenuMutations\TypeAPIs\MenuTypeMutationAPIInterface;
use PoPCMSSchema\Menus\Constants\InputProperties;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
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

abstract class AbstractCreateOrUpdateMenuMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;
    use MenuCRUDMutationResolverTrait;

    private ?MenuTypeMutationAPIInterface $menuTypeMutationAPI = null;
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?NameResolverInterface $nameResolver = null;
    private ?MenuTypeAPIInterface $menuTypeAPI = null;

    final protected function getMenuTypeMutationAPI(): MenuTypeMutationAPIInterface
    {
        if ($this->menuTypeMutationAPI === null) {
            /** @var MenuTypeMutationAPIInterface */
            $menuTypeMutationAPI = $this->instanceManager->getInstance(MenuTypeMutationAPIInterface::class);
            $this->menuTypeMutationAPI = $menuTypeMutationAPI;
        }
        return $this->menuTypeMutationAPI;
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
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        if ($this->userRoleTypeAPI === null) {
            /** @var UserRoleTypeAPIInterface */
            $userRoleTypeAPI = $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
            $this->userRoleTypeAPI = $userRoleTypeAPI;
        }
        return $this->userRoleTypeAPI;
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
    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        if ($this->menuTypeAPI === null) {
            /** @var MenuTypeAPIInterface */
            $menuTypeAPI = $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
            $this->menuTypeAPI = $menuTypeAPI;
        }
        return $this->menuTypeAPI;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $field = $fieldDataAccessor->getField();

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        if ($this->addMenuInputField()) {
            // If updating a menu, check that it exists
            /** @var string|int */
            $menuID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            $this->validateMenuByIDExists(
                $menuID,
                InputProperties::ID,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        // Check that the user is logged-in
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $errorFeedbackItemResolution,
                    $field,
                )
            );
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserEditMenus(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var int|string|null */
        $authorID = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_ID);

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

        // Validate the user can edit the attachment
        if ($this->addMenuInputField()) {
            /** @var string|int */
            $menuID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            $this->validateCanLoggedInUserEditMenu(
                $menuID,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        if ($this->canUploadAttachment()) {
            $currentUserID = App::getState('current-user-id');

            // Validate the user has the needed capability to create menus
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
            } elseif ($authorID !== null && $authorID !== $currentUserID) {
                // Validate the logged-in user has the capability to create menus for other people
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

            // If providing an existing menu, check that it exists
            /** @var stdClass */
            $from = $fieldDataAccessor->getValue(MutationInputProperties::FROM);

            if (isset($from->{MutationInputProperties::MENUITEM_BY})) {
                /** @var stdClass */
                $menuBy = $from->{MutationInputProperties::MENUITEM_BY};
                if (isset($menuBy->{InputProperties::ID})) {
                    /** @var string|int */
                    $menuID = $menuBy->{InputProperties::ID};
                    $this->validateMenuByIDExists(
                        $menuID,
                        MutationInputProperties::FROM,
                        $fieldDataAccessor,
                        $objectTypeFieldResolutionFeedbackStore,
                    );
                } elseif (isset($menuBy->{InputProperties::SLUG})) {
                    /** @var string */
                    $menuSlug = $menuBy->{InputProperties::SLUG};
                    $this->validateMenuBySlugExists(
                        $menuSlug,
                        MutationInputProperties::FROM,
                        $fieldDataAccessor,
                        $objectTypeFieldResolutionFeedbackStore,
                    );
                }
            }
        }

        // Allow components to inject their own validations
        App::doAction(
            MenuCRUDHookNames::VALIDATE_CREATE_OR_UPDATE_MENU_ITEM,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function addMenuInputField(): bool;

    abstract protected function canUploadAttachment(): bool;

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function additionals(string|int $menuID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction(MenuCRUDHookNames::CREATE_OR_UPDATE_MENU_ITEM, $menuID, $fieldDataAccessor);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMenuData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $menuData = [
            'authorID' => $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_ID),
            'title' => $fieldDataAccessor->getValue(MutationInputProperties::TITLE),
            'slug' => $fieldDataAccessor->getValue(MutationInputProperties::SLUG),
            'caption' => $fieldDataAccessor->getValue(MutationInputProperties::CAPTION),
            'description' => $fieldDataAccessor->getValue(MutationInputProperties::DESCRIPTION),
            'altText' => $fieldDataAccessor->getValue(MutationInputProperties::ALT_TEXT),
            'mimeType' => $fieldDataAccessor->getValue(MutationInputProperties::MIME_TYPE),
        ];

        if ($fieldDataAccessor->hasValue(MutationInputProperties::DATE)) {
            /** @var DateTime|null */
            $dateTime = $fieldDataAccessor->getValue(MutationInputProperties::DATE);
            if ($dateTime !== null) {
                $menuData['date'] = $dateTime->format('Y-m-d H:i:s');
            }
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::GMT_DATE)) {
            /** @var DateTime|null */
            $gmtDateTime = $fieldDataAccessor->getValue(MutationInputProperties::GMT_DATE);
            if ($gmtDateTime !== null) {
                $menuData['gmtDate'] = $gmtDateTime->format('Y-m-d H:i:s');
            }
        }

        if ($this->addMenuInputField()) {
            $menuData['id'] = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        }

        // Inject custom post ID, etc
        $menuData = App::applyFilters(MenuCRUDHookNames::GET_CREATE_OR_UPDATE_MENU_ITEM_DATA, $menuData, $fieldDataAccessor);

        return $menuData;
    }
}
