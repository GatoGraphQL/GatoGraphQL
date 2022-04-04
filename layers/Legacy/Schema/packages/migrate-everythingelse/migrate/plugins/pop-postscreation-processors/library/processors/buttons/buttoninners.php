<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_CONTENTPOSTLINK_CREATE = 'buttoninner-postlink-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
        );
        if ($route = $routes[$module[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_CONTENTPOSTLINK_CREATE => TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($module);
    }
}


