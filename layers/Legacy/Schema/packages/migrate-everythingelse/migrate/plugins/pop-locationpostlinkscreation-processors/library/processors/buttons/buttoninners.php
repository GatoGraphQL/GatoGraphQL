<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_SP_Custom_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE = 'buttoninner-locationpostlink-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE],
        );
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        if (defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK') && POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK) {
            $routes = array(
                self::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
            );
            if ($route = $routes[$componentVariation[1]] ?? null) {
                return 'fa-fw '.getRouteIcon($route, false);
            }
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getBtnTitle(array $componentVariation)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_LOCATIONPOSTLINK_CREATE => TranslationAPIFacade::getInstance()->__('as Link', 'pop-locationpostlinkscreation-processors'),
        );
        if ($title = $titles[$componentVariation[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($componentVariation);
    }
}


