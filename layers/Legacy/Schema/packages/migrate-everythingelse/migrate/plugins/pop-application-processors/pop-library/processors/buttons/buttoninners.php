<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_POST_CREATE = 'buttoninner-post-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_POST_CREATE],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_BUTTONINNER_POST_CREATE => POP_POSTSCREATION_ROUTE_ADDPOST,
        );
        if ($route = $routes[$component[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        $titles = array(
            self::COMPONENT_BUTTONINNER_POST_CREATE => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


