<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\MenuMutations\Constants\MenuCRUDHookNames;
use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;
use PoPCMSSchema\Menus\Constants\InputProperties;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use stdClass;

class CreateMenuMutationResolver extends AbstractCreateOrUpdateMenuMutationResolver
{
    protected function addMenuInputField(): bool
    {
        return false;
    }

    protected function canUploadAttachment(): bool
    {
        return true;
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
        /** @var stdClass */
        $from = $fieldDataAccessor->getValue(MutationInputProperties::FROM);

        if (isset($from->{MutationInputProperties::URL})) {
            /** @var stdClass */
            $url = $from->{MutationInputProperties::URL};
            return $this->getMenuTypeMutationAPI()->createMenuFromURL(
                $url->{MutationInputProperties::SOURCE},
                $url->{MutationInputProperties::FILENAME} ?? null,
                $menuData,
            );
        }

        if (isset($from->{MutationInputProperties::MENUITEM_BY})) {
            /** @var string|int|null */
            $menuID = null;
            /** @var stdClass */
            $menuBy = $from->{MutationInputProperties::MENUITEM_BY};
            if (isset($menuBy->{InputProperties::ID})) {
                $menuID = $menuBy->{InputProperties::ID};
            } elseif (isset($menuBy->{InputProperties::SLUG})) {
                $menuTypeAPI = $this->getMenuTypeAPI();
                /** @var string */
                $menuSlug = $menuBy->{InputProperties::SLUG};
                /** @var object */
                $menu = $menuTypeAPI->getMenuBySlug($menuSlug);
                $menuID = $menuTypeAPI->getMenuID($menu);
            }
            if ($menuID === null) {
                return null;
            }
            return $this->getMenuTypeMutationAPI()->createMenuFromExistingMenu(
                $menuID,
                $menuData,
            );
        }

        /** @var stdClass */
        $contents = $from->{MutationInputProperties::CONTENTS};

        return $this->getMenuTypeMutationAPI()->createMenuFromContents(
            $contents->{MutationInputProperties::BODY},
            $contents->{MutationInputProperties::FILENAME},
            $menuData,
        );
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
