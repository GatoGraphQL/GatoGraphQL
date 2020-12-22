<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_EventLinksCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_BUTTONINNER_EVENTLINK_CREATE = 'buttoninner-eventlink-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_EVENTLINK_CREATE],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_EVENTLINK_CREATE => POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
        );
        if ($route = $routes[$module[1]]) {
            return 'fa-fw '.getRouteIcon($route, false);
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_EVENTLINK_CREATE => TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup'),//TranslationAPIFacade::getInstance()->__('Event link', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]]) {
            return $title;
        }
        
        return parent::getBtnTitle($module);
    }
}


