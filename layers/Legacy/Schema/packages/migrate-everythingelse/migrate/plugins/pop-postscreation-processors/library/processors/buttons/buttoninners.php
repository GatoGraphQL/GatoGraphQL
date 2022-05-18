<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_CONTENTPOSTLINK_CREATE = 'buttoninner-postlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
        );
        if ($route = $routes[$component[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        $titles = array(
            self::COMPONENT_BUTTONINNER_CONTENTPOSTLINK_CREATE => TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


