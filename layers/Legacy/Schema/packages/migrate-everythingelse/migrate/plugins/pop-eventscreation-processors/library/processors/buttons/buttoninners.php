<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_EVENT_CREATE = 'buttoninner-event-create';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONINNER_EVENT_CREATE,
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_BUTTONINNER_EVENT_CREATE => POP_EVENTSCREATION_ROUTE_ADDEVENT,
        );
        if ($route = $routes[$component->name] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        $titles = array(
            self::COMPONENT_BUTTONINNER_EVENT_CREATE => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


