<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutationsWP\TypeAPIs;

use PoPCMSSchema\MenuMutationsWP\Constants\HookNames;
use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\Enums\MenuItemType;
use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;
use PoPCMSSchema\MenuMutations\TypeAPIs\MenuTypeMutationAPIInterface;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;
use stdClass;
use WP_Error;

use function esc_url_raw;
use function get_post_type;
use function get_taxonomy;
use function get_term;
use function get_theme_mod;
use function is_wp_error;
use function sanitize_html_class;
use function sanitize_text_field;
use function sanitize_textarea_field;
use function sanitize_title;
use function set_theme_mod;
use function user_can;
use function wp_delete_post;
use function wp_get_nav_menu_items;
use function wp_insert_term;
use function wp_update_nav_menu_item;
use function wp_update_term;

class MenuTypeMutationAPI extends AbstractBasicService implements MenuTypeMutationAPIInterface
{
    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    public function updateMenu(
        string|int $menuID,
        array $menuData,
    ): void {
        $termArgs = $this->convertMenuCreationArgs($menuData);

        $termIDOrError = wp_update_term(
            (int) $menuID,
            'nav_menu',
            $termArgs,
        );

        if (is_wp_error($termIDOrError)) {
            /** @var WP_Error */
            $wpError = $termIDOrError;
            throw new MenuCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        $this->maybeCreateOrReplaceMenuItems(
            (int) $menuID,
            $menuData,
            true,
        );

        $this->maybeAssignMenuLocations(
            (int) $menuID,
            $menuData,
        );
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    public function createMenu(
        array $menuData,
    ): string|int {
        $termArgs = $this->convertMenuCreationArgs($menuData);

        $menuName = $termArgs['name'] ?? '';
        $menuName = trim($menuName);
        if ($menuName === '') {
            throw new MenuCRUDMutationException(
                $this->__('The menu name cannot be empty', 'menu-mutations')
            );
        }
        $menuName = sanitize_text_field($menuName);

        // `wp_insert_term` expects the term name separately from the args.
        unset($termArgs['name']);

        $termOrError = wp_insert_term(
            $menuName,
            'nav_menu',
            $termArgs,
        );

        if (is_wp_error($termOrError)) {
            /** @var WP_Error */
            $wpError = $termOrError;
            throw new MenuCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        /** @var array{term_id:int,term_taxonomy_id:int} $termData */
        $termData = $termOrError;
        $menuID = $termData['term_id'];

        $this->maybeCreateOrReplaceMenuItems(
            $menuID,
            $menuData,
            false,
        );

        $this->maybeAssignMenuLocations(
            $menuID,
            $menuData,
        );

        return $menuID;
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    protected function maybeCreateOrReplaceMenuItems(
        int $menuID,
        array $menuData,
        bool $replaceExistingMenuItems,
    ): void {
        $jsonItems = $menuData['json-items'] ?? null;
        if ($jsonItems === null) {
            return;
        }

        $menuItemsData = $this->normalizeMenuItemsData($jsonItems);

        if ($replaceExistingMenuItems) {
            $this->deleteAllMenuItems($menuID);
        }

        $this->createMenuItemsRecursive(
            $menuID,
            $menuItemsData,
            0,
        );
    }

    /**
     * @param mixed $menuItemsValue
     * @return array<int,array<string,mixed>>
     */
    protected function normalizeMenuItemsData(mixed $menuItemsValue): array
    {
        if (!is_array($menuItemsValue)) {
            return [];
        }

        $menuItemsData = [];
        foreach ($menuItemsValue as $menuItemValue) {
            $menuItemData = $this->normalizeMenuItemData($menuItemValue);
            if ($menuItemData === []) {
                continue;
            }
            $menuItemsData[] = $menuItemData;
        }

        return $menuItemsData;
    }

    /**
     * @param mixed $menuItemValue
     * @return array<string,mixed>
     */
    protected function normalizeMenuItemData(mixed $menuItemValue): array
    {
        if ($menuItemValue instanceof stdClass) {
            $menuItemValue = (array) $menuItemValue;
        }

        if (!is_array($menuItemValue)) {
            return [];
        }

        if (array_key_exists(MutationInputProperties::CHILDREN, $menuItemValue)) {
            $childrenValue = $menuItemValue[MutationInputProperties::CHILDREN];
            $menuItemValue[MutationInputProperties::CHILDREN] = $this->normalizeMenuItemsData($childrenValue);
        }

        return $menuItemValue;
    }

    protected function deleteAllMenuItems(int $menuID): void
    {
        $menuItems = wp_get_nav_menu_items(
            $menuID,
            [
                'post_status' => 'any',
            ],
        );
        if (!is_array($menuItems)) {
            return;
        }

        foreach ($menuItems as $menuItem) {
            if (!isset($menuItem->ID)) {
                continue;
            }
            wp_delete_post((int) $menuItem->ID, true);
        }
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<int,array<string,mixed>> $menuItemsData
     */
    protected function createMenuItemsRecursive(
        int $menuID,
        array $menuItemsData,
        int $parentMenuItemID,
    ): void {
        foreach ($menuItemsData as $menuItemData) {
            $createdMenuItemID = $this->createMenuItem(
                $menuID,
                $menuItemData,
                $parentMenuItemID,
            );

            /** @var array<int,array<string,mixed>> $children */
            $children = $menuItemData[MutationInputProperties::CHILDREN] ?? [];
            if ($children !== []) {
                $this->createMenuItemsRecursive(
                    $menuID,
                    $children,
                    $createdMenuItemID,
                );
            }
        }
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuItemData
     */
    protected function createMenuItem(
        int $menuID,
        array $menuItemData,
        int $parentMenuItemID,
    ): int {
        $label = $menuItemData[MutationInputProperties::LABEL] ?? null;
        $title = $label !== null ? (string) $label : '';
        $title = trim($title);
        if ($title !== '') {
            $title = sanitize_text_field($title);
        }

        $url = (string) ($menuItemData[MutationInputProperties::URL] ?? '');
        $url = trim($url);
        if ($url !== '') {
            $url = esc_url_raw($url);
        }

        $description = (string) ($menuItemData[MutationInputProperties::DESCRIPTION] ?? '');
        $description = trim($description);
        if ($description !== '') {
            $description = sanitize_textarea_field($description);
        }

        $cssClasses = $menuItemData[MutationInputProperties::CSS_CLASSES] ?? [];
        $cssClasses = is_array($cssClasses) ? $cssClasses : [];
        $cssClasses = array_values(array_filter(array_map(
            static fn (mixed $className): string => sanitize_html_class((string) $className),
            $cssClasses
        )));

        $target = (string) ($menuItemData[MutationInputProperties::TARGET] ?? '');
        $target = $this->sanitizeMenuItemTarget($target);

        $linkRelationship = (string) ($menuItemData[MutationInputProperties::LINK_RELATIONSHIP] ?? '');
        $linkRelationship = trim($linkRelationship);
        if ($linkRelationship !== '') {
            $linkRelationship = sanitize_text_field($linkRelationship);
        }

        $titleAttribute = (string) ($menuItemData[MutationInputProperties::TITLE_ATTRIBUTE] ?? '');
        $titleAttribute = trim($titleAttribute);
        if ($titleAttribute !== '') {
            $titleAttribute = sanitize_text_field($titleAttribute);
        }

        $itemType = (string) ($menuItemData[MutationInputProperties::ITEM_TYPE] ?? '');
        $itemType = trim($itemType);
        $itemType = in_array(
            $itemType,
            [
                MenuItemType::CUSTOM,
                MenuItemType::POST_TYPE,
                MenuItemType::TAXONOMY,
            ],
            true,
        ) ? $itemType : '';

        $objectType = (string) ($menuItemData[MutationInputProperties::OBJECT_TYPE] ?? '');
        $objectType = trim($objectType);
        if ($objectType !== '') {
            $objectType = sanitize_text_field($objectType);
        }

        $objectID = $menuItemData[MutationInputProperties::OBJECT_ID] ?? null;
        $objectID = is_numeric($objectID) ? (int) $objectID : 0;

        /**
         * Backward-compatible behavior: if `itemType` is not provided, try to infer it.
         */
        if ($itemType === '' && $objectID > 0) {
            $postType = get_post_type($objectID);
            if (is_string($postType) && $postType !== '') {
                $itemType = MenuItemType::POST_TYPE;
                $objectType = $postType;
            }
        }

        $args = match ($itemType) {
            MenuItemType::POST_TYPE => $this->createPostTypeMenuItemArgs($title, $objectType, $objectID),
            MenuItemType::TAXONOMY => $this->createTaxonomyMenuItemArgs($title, $objectType, $objectID),
            default => [
                'menu-item-type' => 'custom',
                'menu-item-object' => 'custom',
                'menu-item-object-id' => 0,
                'menu-item-title' => $title,
                'menu-item-url' => $url,
                'menu-item-status' => 'publish',
            ],
        };

        if ($description !== '') {
            $args['menu-item-description'] = $description;
        }

        if ($cssClasses !== []) {
            $args['menu-item-classes'] = implode(' ', $cssClasses);
        }

        if ($target !== '') {
            $args['menu-item-target'] = $target;
        }

        if ($linkRelationship !== '') {
            $args['menu-item-xfn'] = $linkRelationship;
        }

        if ($titleAttribute !== '') {
            $args['menu-item-attr-title'] = $titleAttribute;
        }

        if ($parentMenuItemID > 0) {
            $args['menu-item-parent-id'] = $parentMenuItemID;
        }

        $menuItemIDOrError = wp_update_nav_menu_item(
            $menuID,
            0,
            $args,
        );

        if (is_wp_error($menuItemIDOrError)) {
            /** @var WP_Error */
            $wpError = $menuItemIDOrError;
            throw new MenuCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        return (int) $menuItemIDOrError;
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @return array<string,mixed>
     */
    protected function createPostTypeMenuItemArgs(string $title, string $postType, int $objectID): array
    {
        if ($postType === '' || $objectID <= 0) {
            throw new MenuCRUDMutationException(
                $this->__('For menu items of type "post_type", both "objectType" and "objectID" are required', 'menu-mutations')
            );
        }

        return [
            'menu-item-type' => 'post_type',
            'menu-item-object' => $postType,
            'menu-item-object-id' => $objectID,
            'menu-item-title' => $title,
            'menu-item-status' => 'publish',
        ];
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @return array<string,mixed>
     */
    protected function createTaxonomyMenuItemArgs(string $title, string $taxonomyName, int $objectID): array
    {
        if ($taxonomyName === '' || $objectID <= 0) {
            throw new MenuCRUDMutationException(
                $this->__('For menu items of type "taxonomy", both "objectType" and "objectID" are required', 'menu-mutations')
            );
        }

        return [
            'menu-item-type' => 'taxonomy',
            'menu-item-object' => $taxonomyName,
            'menu-item-object-id' => $objectID,
            'menu-item-title' => $title,
            'menu-item-status' => 'publish',
        ];
    }

    protected function sanitizeMenuItemTarget(string $target): string
    {
        $target = trim($target);
        if ($target === '') {
            return '';
        }

        return in_array(
            $target,
            [
                '_blank',
                '_self',
                '_parent',
                '_top',
            ],
            true,
        ) ? $target : '';
    }

    /**
     * @param array<string,mixed> $menuData
     * @return array<string,mixed>
     */
    protected function convertMenuCreationArgs(array $menuData): array
    {
        /**
         * Nav menus are stored as terms in the `nav_menu` taxonomy.
         *
         * Map the mutation input properties to the supported term args.
         *
         * @see wp_insert_term()
         * @see wp_update_term()
         */
        $termArgs = [];

        $name = $menuData['name'] ?? null;
        if ($name !== null) {
            $name = trim((string) $name);
            if ($name !== '') {
                $termArgs['name'] = sanitize_text_field($name);
            }
        }

        $slug = $menuData['slug'] ?? null;
        if ($slug !== null) {
            $slug = trim((string) $slug);
            if ($slug !== '') {
                $termArgs['slug'] = sanitize_title($slug);
            }
        }

        return $termArgs;
    }

    public function canUserEditMenus(
        string|int $userID
    ): bool {
        $navMenuTaxonomy = get_taxonomy('nav_menu');
        if (!$navMenuTaxonomy) {
            return false;
        }

        if (isset($navMenuTaxonomy->cap->manage_terms)) {
            return user_can((int) $userID, $navMenuTaxonomy->cap->manage_terms);
        }

        // Fallback: `nav_menu` taxonomy capabilities map to `edit_theme_options`.
        return user_can((int) $userID, 'edit_theme_options');
    }

    public function canUserEditMenu(
        string|int $userID,
        string|int $menuID,
    ): bool {
        $menuTerm = get_term((int) $menuID, 'nav_menu');
        if (
            $menuTerm === null
            || is_wp_error($menuTerm)
            // || !($menuTerm instanceof WP_Term)
            || $menuTerm->taxonomy !== 'nav_menu'
        ) {
            return false;
        }

        return user_can((int) $userID, 'edit_term', (int) $menuID);
    }

    /**
     * @param array<string,mixed> $menuData
     */
    protected function maybeAssignMenuLocations(
        int $menuID,
        array $menuData,
    ): void {
        $locations = $menuData['locations'] ?? null;
        if ($locations === null || !is_array($locations)) {
            return;
        }

        // Get current menu locations
        $navMenuLocations = get_theme_mod('nav_menu_locations', []);
        if (!is_array($navMenuLocations)) {
            $navMenuLocations = [];
        }

        // Remove menu from all locations where it's currently assigned
        foreach ($navMenuLocations as $location => $assignedMenuID) {
            if ((int) $assignedMenuID === $menuID) {
                $navMenuLocations[$location] = 0;
            }
        }

        // Assign menu to specified locations
        foreach ($locations as $location) {
            if (!is_string($location)) {
                continue;
            }
            $navMenuLocations[$location] = $menuID;
        }

        // Save menu locations
        set_theme_mod('nav_menu_locations', $navMenuLocations);

        /**
         * Allow Polylang to update the menu locations
         */
        App::doAction(HookNames::MENU_LOCATIONS_UPDATED, $navMenuLocations, $menuID);
    }
}
