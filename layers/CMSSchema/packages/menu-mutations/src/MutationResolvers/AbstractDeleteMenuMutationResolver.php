<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;
use PoPCMSSchema\MenuMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MenuMutations\TypeAPIs\MenuTypeMutationAPIInterface;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractDeleteMenuMutationResolver extends AbstractMutationResolver
{
    use DeleteMenuMutationResolverTrait;
    use ValidateUserLoggedInMutationResolverTrait;

    private ?MenuTypeAPIInterface $menuTypeAPI = null;
    private ?MenuTypeMutationAPIInterface $menuTypeMutationAPI = null;

    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        if ($this->menuTypeAPI === null) {
            /** @var MenuTypeAPIInterface */
            $menuTypeAPI = $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
            $this->menuTypeAPI = $menuTypeAPI;
        }
        return $this->menuTypeAPI;
    }

    final protected function getMenuTypeMutationAPI(): MenuTypeMutationAPIInterface
    {
        if ($this->menuTypeMutationAPI === null) {
            /** @var MenuTypeMutationAPIInterface */
            $menuTypeMutationAPI = $this->instanceManager->getInstance(MenuTypeMutationAPIInterface::class);
            $this->menuTypeMutationAPI = $menuTypeMutationAPI;
        }
        return $this->menuTypeMutationAPI;
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E10,
        );
    }

    protected function validateDeleteErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        /** @var string|int|null */
        $menuID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateMenuExists($menuID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int */
        $menuID = $menuID;

        $this->validateCanLoggedInUserDeleteMenu($menuID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Validate that the menu exists. The ID is mandatory in the input,
     * so a missing ID should never happen.
     */
    protected function validateMenuExists(
        string|int|null $menuID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$menuID || $this->getMenuTypeAPI()->getMenu($menuID) === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E6,
                        [
                            $menuID ?? '',
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    /**
     * Check that the logged-in user can delete this specific menu.
     *
     * Menus are terms in the `nav_menu` taxonomy, hence the `delete_term`
     * meta capability is resolved by the CMS against that taxonomy's
     * capabilities, which map to `edit_theme_options`.
     */
    protected function validateCanLoggedInUserDeleteMenu(
        string|int $menuID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $userID = App::getState('current-user-id');
        if ($this->getMenuTypeMutationAPI()->canUserDeleteMenu($userID, $menuID)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E11,
                    [
                        $menuID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    /**
     * @return bool Whether the menu was deleted
     * @throws MenuCRUDMutationException If there was an error (eg: Menu does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool {
        /** @var string|int */
        $menuID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        $this->getMenuTypeMutationAPI()->deleteMenu($menuID);

        return true;
    }
}
