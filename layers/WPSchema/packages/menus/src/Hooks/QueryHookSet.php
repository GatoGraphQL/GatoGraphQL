<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\MenusWP\TypeAPIs\MenuTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            MenuTypeAPI::HOOK_QUERY,
            [$this, 'convertMenuQuery'],
            10,
            2
        );
    }

    public function convertMenuQuery(array $query, array $options): array
    {
        if (isset($query['locations'])) {
            if ($query['locations'] !== []) {
                // Include all menu IDs using the given locations
                $locationMenuIDs = \get_nav_menu_locations();
                $menuIDs = [];
                foreach ($query['locations'] as $location) {
                    $menuID = $locationMenuIDs[$location] ?? null;
                    // If the location doesn't have a menu assigned, it will be assigned "0"
                    if ($menuID === null || (int) $menuID === 0) {
                        continue;
                    }
                    $menuIDs[] = $menuID;
                }
                if ($menuIDs === []) {
                    // If no menu was found with the location, then return no results
                    // via ID '-1', which does not exist
                    $query['include'] = -1;
                } elseif ($query['include']) {
                    // If other IDs had been requested already, do the intersection among them
                    $intersectedMenuIDs = array_intersect(
                        explode(',', $query['include']),
                        $menuIDs
                    );
                    $query['include'] = $intersectedMenuIDs === [] ? -1 : implode(',', $intersectedMenuIDs);
                } else {
                    // Limit to the found IDs
                    $query['include'] = implode(',', $menuIDs);
                }
            }
            unset($query['locations']);
        }
        return $query;
    }
}
