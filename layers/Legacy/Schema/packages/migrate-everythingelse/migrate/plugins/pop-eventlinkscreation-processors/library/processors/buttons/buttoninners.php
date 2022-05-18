<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventLinksCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_EVENTLINK_CREATE = 'buttoninner-eventlink-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_EVENTLINK_CREATE],
        );
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_EVENTLINK_CREATE => POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
        );
        if ($route = $routes[$componentVariation[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getBtnTitle(array $componentVariation)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_EVENTLINK_CREATE => TranslationAPIFacade::getInstance()->__('as Link', 'poptheme-wassup'),//TranslationAPIFacade::getInstance()->__('Event link', 'poptheme-wassup'),
        );
        if ($title = $titles[$componentVariation[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($componentVariation);
    }
}


