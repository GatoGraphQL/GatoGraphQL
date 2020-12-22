<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_SP_Custom_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE = 'buttoninner-locationpostlink-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        if (defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK') && POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK) {
            $routes = array(
                self::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
            );
            if ($route = $routes[$module[1]]) {
                return 'fa-fw '.getRouteIcon($route, false);
            }
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE => TranslationAPIFacade::getInstance()->__('as Link', 'pop-locationpostlinkscreation-processors'),
        );
        if ($title = $titles[$module[1]]) {
            return $title;
        }
        
        return parent::getBtnTitle($module);
    }
}


