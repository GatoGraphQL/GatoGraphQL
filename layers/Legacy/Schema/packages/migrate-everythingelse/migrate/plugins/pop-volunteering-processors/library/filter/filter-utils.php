<?php

class PoP_VolunteeringProcessors_FilterUtils
{
    private static $volunteer_modules;

    public static function getVolunteerComponentVariations()
    {
        if (is_null(self::$volunteer_modules)) {
            $volunteer_modules = \PoP\Root\App::applyFilters(
                'PoP_VolunteeringProcessors_FilterUtils:volunteer-modules',
                array()
            );
            self::$volunteer_modules = [];
            foreach ($volunteer_modules as $volunteer_module) {
                $componentVariation = $volunteer_module['component-variation'];
                $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
                self::$volunteer_modules[$moduleFullName] = $volunteer_module['volunteerModule'];
            }
        }

        return self::$volunteer_modules;
    }

    public static function maybeAddVolunteerFilterinput($filterinputs, array $componentVariation)
    {
        if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
            $volunteer_modules = self::getVolunteerComponentVariations();
            $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
            if ($volunteer_module = $volunteer_modules[$moduleFullName] ?? null) {
                $filterinputs[] = $volunteer_module;
            }
        }

        return $filterinputs;
    }
}
