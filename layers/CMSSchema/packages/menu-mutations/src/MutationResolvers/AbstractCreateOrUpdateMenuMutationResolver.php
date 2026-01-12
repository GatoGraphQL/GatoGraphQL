<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\MenuMutations\Constants\MenuCRUDHookNames;
use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MenuMutations\MutationResolvers\MenuCRUDMutationResolverTrait;
use PoPCMSSchema\MenuMutations\TypeAPIs\MenuTypeMutationAPIInterface;
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
                MutationInputProperties::ID,
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

        // Validate the user can edit the menu
        if ($this->addMenuInputField()) {
            /** @var string|int */
            $menuID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            $this->validateCanLoggedInUserEditMenu(
                $menuID,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        // Allow components to inject their own validations
        App::doAction(
            MenuCRUDHookNames::VALIDATE_CREATE_OR_UPDATE_MENU_ITEM,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function addMenuInputField(): bool;

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
            'name' => $fieldDataAccessor->getValue(MutationInputProperties::NAME),
            'slug' => $fieldDataAccessor->getValue(MutationInputProperties::SLUG),
            'locations' => $fieldDataAccessor->getValue(MutationInputProperties::LOCATIONS),
        ];

        $itemsBy = $fieldDataAccessor->getValue(MutationInputProperties::ITEMS_BY);
        if (($itemsBy->{MutationInputProperties::JSON} ?? null) !== null) {
            $menuData['json-items'] = $itemsBy->{MutationInputProperties::JSON};
        }

        if ($this->addMenuInputField()) {
            $menuData['id'] = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        }

        // Inject custom post ID, etc
        $menuData = App::applyFilters(MenuCRUDHookNames::GET_CREATE_OR_UPDATE_MENU_ITEM_DATA, $menuData, $fieldDataAccessor);

        return $menuData;
    }
}
