<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenusWP\TypeAPIs;

use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\Menus\ObjectModels\MenuItem;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use WP_Term;

use function esc_sql;
use function wp_get_nav_menus;

class MenuTypeAPI implements MenuTypeAPIInterface
{
    use BasicServiceTrait;

    public const HOOK_QUERY = __CLASS__ . ':query';

    public function getMenu(string|int $menuID): ?object
    {
        $object = wp_get_nav_menu_object($menuID);
        // If the object is not found, it returns `false`. Return `null` instead
        if ($object === false) {
            return null;
        }
        return $object;
    }
    /**
     * @return MenuItem[]
     */
    public function getMenuItems(string|int|object $menuObjectOrID): array
    {
        /** @var string|int|WP_Term $menuObjectOrID */
        $menuItems = wp_get_nav_menu_items($menuObjectOrID);
        if ($menuItems === false) {
            return [];
        }
        /**
         * Convert from the object returned by `wp_get_nav_menu_items` to ObjectModels\MenuItem
         */
        return array_map(
            function (object $menuItem): MenuItem {
                return new MenuItem(
                    $menuItem->ID,
                    (int) $menuItem->object_id,
                    $menuItem->menu_item_parent === "0" ? null : (int) $menuItem->menu_item_parent,
                    \apply_filters('the_title', $menuItem->title, $menuItem->object_id),
                    $menuItem->title,
                    $menuItem->attr_title,
                    $menuItem->url,
                    $menuItem->description,
                    array_filter($menuItem->classes),
                    $menuItem->target,
                    $menuItem->xfn,
                );
            },
            $menuItems
        );
    }

    public function getMenuID(object $menu): string|int
    {
        return $menu->term_id;
    }

    public function getMenuIDFromMenuName(string $menuName): string|int|null
    {
        $menuObject = $this->getMenuObject($menuName);
        if ($menuObject === null) {
            return null;
        }
        return $menuObject->term_id;
    }

    protected function getMenuObject(string $menuName): ?WP_Term
    {
        $locations = \get_nav_menu_locations();
        $menuID = $locations[$menuName] ?? null;
        if ($menuID === null) {
            return null;
        }
        /** @var WP_Term|null */
        return $this->getMenu($menuID);
    }

    /**
     * @param array<string,mixed> $options
     * @return array<string|int|object>
     * @param array<string,mixed> $query
     */
    public function getMenus(array $query, array $options = []): array
    {
        $query = $this->convertMenusQuery($query, $options);

        // If passing an empty array to `filter.ids`, return no results
        if ($this->isFilteringByEmptyArray($query)) {
            return [];
        }

        return wp_get_nav_menus($query);
    }

    /**
     * Indicate if an empty array was passed to `filter.ids`
     *
     * @param array<string,mixed> $query
     */
    protected function isFilteringByEmptyArray(array $query): bool
    {
        return isset($query['include']) && ($query['include'] === '' || $query['include'] === []);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMenuCount(array $query, array $options = []): int
    {
        $query = $this->convertMenusQuery($query, $options);

        // If passing an empty array to `filter.ids`, return no results
        if ($this->isFilteringByEmptyArray($query)) {
            return 0;
        }

        // Indicate to return the count
        $query['count'] = true;
        $query['fields'] = 'count';

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Execute query and return count
        /** @var string */
        $count = wp_get_nav_menus($query);
        return (int)$count;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertMenusQuery(array $query, array $options = []): array
    {
        if ($return_type = $options[QueryOptions::RETURN_TYPE] ?? null) {
            if ($return_type === ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            } elseif ($return_type === ReturnTypes::NAMES) {
                $query['fields'] = 'names';
            }
        }

        if (isset($query['hide-empty'])) {
            $query['hide_empty'] = $query['hide-empty'];
            unset($query['hide-empty']);
        } else {
            // By default: do not hide empty categories
            $query['hide_empty'] = false;
        }

        // Convert the parameters
        if (isset($query['include']) && is_array($query['include'])) {
            // It can be an array or a string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['exclude'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        if (isset($query['order'])) {
            $query['order'] = esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
            $query['orderby'] = esc_sql($query['orderby']);
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            $limit = (int) $query['limit'];

            // Assign the limit as the required attribute
            // To bring all results, get_categories needs "number => 0" instead of -1
            $query['number'] = ($limit === -1) ? 0 : $limit;
            unset($query['limit']);
        }
        if (isset($query['search'])) {
            // Same param name, so do nothing
        }
        if (isset($query['slugs'])) {
            $query['slug'] = $query['slugs'];
            unset($query['slugs']);
        }
        if (isset($query['slug'])) {
            // Same param name, so do nothing
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
}
