<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

class PoP_VolunteeringProcessors_FilterUtils
{
    private static $volunteer_modules;

    public static function getVolunteerModules()
    {
        if (is_null(self::$volunteer_modules)) {
            $volunteer_modules = HooksAPIFacade::getInstance()->applyFilters(
                'PoP_VolunteeringProcessors_FilterUtils:volunteer-modules',
                array()
            );
            self::$volunteer_modules = [];
            foreach ($volunteer_modules as $volunteer_module) {
                $module = $volunteer_module['module'];
                $moduleFullName = ModuleUtils::getModuleFullName($module);
                self::$volunteer_modules[$moduleFullName] = $volunteer_module['volunteerModule'];
            }
        }

        return self::$volunteer_modules;
    }

    public static function maybeAddVolunteerFilterinput($filterinputs, array $module)
    {
        if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
            $volunteer_modules = self::getVolunteerModules();
            $moduleFullName = ModuleUtils::getModuleFullName($module);
            if ($volunteer_module = $volunteer_modules[$moduleFullName] ?? null) {
                $filterinputs[] = $volunteer_module;
            }
        }

        return $filterinputs;
    }
}
