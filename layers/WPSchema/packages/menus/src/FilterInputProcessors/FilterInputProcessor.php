<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_LOCATIONS = 'filterinput-locations';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_LOCATIONS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_LOCATIONS:
                if (empty($value)) {
                    return;
                }
                // Include all menu IDs using the given locations
                $locations = \get_nav_menu_locations();
                $menuIDs = [];
                foreach ($value as $location) {
                    $menuID = $locations[$location] ?? null;
                    // If the location doesn't have a menu assigned, it will be assigned "0"
                    if ($menuID === null || (int) $menuID === 0) {
                        continue;
                    }
                    $menuIDs[] = $menuID;
                }
                if ($menuIDs !== []) {
                    $query['include'] = implode(',', $menuIDs);
                    return;
                }
                // No menu was found with the location, then return nothing
                $query['include'] = -1;
                break;
        }
    }
}
