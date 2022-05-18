<?php

class PoP_VolunteeringProcessors_FilterUtils
{
    private static $volunteer_componentVariations;

    public static function getVolunteerComponentVariations()
    {
        if (is_null(self::$volunteer_componentVariations)) {
            $volunteer_componentVariations = \PoP\Root\App::applyFilters(
                'PoP_VolunteeringProcessors_FilterUtils:volunteer-modules',
                array()
            );
            self::$volunteer_componentVariations = [];
            foreach ($volunteer_componentVariations as $volunteer_componentVariation) {
                $componentVariation = $volunteer_componentVariation['component-variation'];
                $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
                self::$volunteer_componentVariations[$moduleFullName] = $volunteer_componentVariation['volunteerModule'];
            }
        }

        return self::$volunteer_componentVariations;
    }

    public static function maybeAddVolunteerFilterinput($filterinputs, array $componentVariation)
    {
        if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
            $volunteer_componentVariations = self::getVolunteerComponentVariations();
            $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
            if ($volunteer_componentVariation = $volunteer_componentVariations[$moduleFullName] ?? null) {
                $filterinputs[] = $volunteer_componentVariation;
            }
        }

        return $filterinputs;
    }
}
