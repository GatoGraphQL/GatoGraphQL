<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_EVENT_CREATE = 'buttoninner-event-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_EVENT_CREATE],
        );
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_EVENT_CREATE => POP_EVENTSCREATION_ROUTE_ADDEVENT,
        );
        if ($route = $routes[$componentVariation[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getBtnTitle(array $componentVariation)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_EVENT_CREATE => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );
        if ($title = $titles[$componentVariation[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($componentVariation);
    }
}


