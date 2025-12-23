<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\MenuMutations\Constants\MenuCRUDHookNames;
use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;

class CreateMenuMutationResolver extends AbstractCreateOrUpdateMenuMutationResolver
{
    protected function addMenuInputField(): bool
    {
        return false;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validate(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        // Allow components to inject their own validations
        App::doAction(
            MenuCRUDHookNames::VALIDATE_CREATE_MENU_ITEM,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function additionals(string|int $menuID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($menuID, $fieldDataAccessor);
        App::doAction(MenuCRUDHookNames::CREATE_MENU_ITEM, $menuID, $fieldDataAccessor);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMenuData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return App::applyFilters(
            MenuCRUDHookNames::GET_CREATE_MENU_ITEM_DATA,
            parent::getMenuData($fieldDataAccessor),
            $fieldDataAccessor
        );
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    protected function createMenu(
        array $menuData,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int|null {
        return $this->getMenuTypeMutationAPI()->createMenu($menuData);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $menuData = $this->getMenuData($fieldDataAccessor);

        $menuID = $this->createMenu(
            $menuData,
            $fieldDataAccessor,
        );

        if ($menuID === null) {
            return null;
        }

        // Allow for additional operations
        $this->additionals($menuID, $fieldDataAccessor);

        return $menuID;
    }
}
