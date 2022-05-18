<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_HIGHLIGHT_CREATE = 'buttoninner-highlight-create';
    public final const COMPONENT_BUTTONINNER_HIGHLIGHT_CREATEBTN = 'buttoninner-highlight-createbtn';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATE],
            [self::class, self::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATEBTN],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATEBTN => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
        );
        if ($route = $routes[$component[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        $extract = TranslationAPIFacade::getInstance()->__('Add Highlight', 'poptheme-wassup');
        $titles = array(
            self::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATE => $extract,
            self::COMPONENT_BUTTONINNER_HIGHLIGHT_CREATEBTN => $extract,
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


