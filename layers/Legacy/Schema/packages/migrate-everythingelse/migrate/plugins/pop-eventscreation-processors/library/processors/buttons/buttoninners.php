<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_EVENT_CREATE = 'buttoninner-event-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_EVENT_CREATE],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_EVENT_CREATE => POP_EVENTSCREATION_ROUTE_ADDEVENT,
        );
        if ($route = $routes[$component[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_EVENT_CREATE => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


