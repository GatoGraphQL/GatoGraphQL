<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_BUTTONINNER_POST_CREATE = 'buttoninner-post-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_POST_CREATE],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_POST_CREATE => POP_POSTSCREATION_ROUTE_ADDPOST,
        );
        if ($route = $routes[$module[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_POST_CREATE => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($module);
    }
}


