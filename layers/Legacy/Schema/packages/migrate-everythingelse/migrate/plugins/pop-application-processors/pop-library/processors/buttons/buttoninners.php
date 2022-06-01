<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_POST_CREATE = 'buttoninner-post-create';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONINNER_POST_CREATE,
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_BUTTONINNER_POST_CREATE => POP_POSTSCREATION_ROUTE_ADDPOST,
        );
        if ($route = $routes[$component->name] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        $titles = array(
            self::COMPONENT_BUTTONINNER_POST_CREATE => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


