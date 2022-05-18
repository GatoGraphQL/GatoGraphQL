<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_SP_Custom_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE = 'buttoninner-locationpostlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_LOCATIONPOSTLINK_CREATE],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        if (defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK') && POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK) {
            $routes = array(
                self::COMPONENT_BUTTONINNER_LOCATIONPOSTLINK_CREATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
            );
            if ($route = $routes[$component[1]] ?? null) {
                return 'fa-fw '.getRouteIcon($route, false);
            }
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        $titles = array(
            self::COMPONENT_BUTTONINNER_LOCATIONPOSTLINK_CREATE => TranslationAPIFacade::getInstance()->__('as Link', 'pop-locationpostlinkscreation-processors'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


