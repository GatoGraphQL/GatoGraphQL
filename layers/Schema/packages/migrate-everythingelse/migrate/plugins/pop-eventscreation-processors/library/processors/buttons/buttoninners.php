<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_BUTTONINNER_EVENT_CREATE = 'buttoninner-event-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_EVENT_CREATE],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_EVENT_CREATE => POP_EVENTSCREATION_ROUTE_ADDEVENT,
        );
        if ($route = $routes[$module[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_EVENT_CREATE => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($module);
    }
}


