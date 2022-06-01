<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE = 'buttoninner-postlink-create';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE,
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
        );
        if ($route = $routes[$component->name] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        $titles = array(
            self::COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE => TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup'),
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


