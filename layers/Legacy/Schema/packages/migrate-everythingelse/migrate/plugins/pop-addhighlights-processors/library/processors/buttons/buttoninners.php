<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_HIGHLIGHT_CREATE = 'buttoninner-highlight-create';
    public final const MODULE_BUTTONINNER_HIGHLIGHT_CREATEBTN = 'buttoninner-highlight-createbtn';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_HIGHLIGHT_CREATE],
            [self::class, self::MODULE_BUTTONINNER_HIGHLIGHT_CREATEBTN],
        );
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::MODULE_BUTTONINNER_HIGHLIGHT_CREATEBTN => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
        );
        if ($route = $routes[$componentVariation[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getBtnTitle(array $componentVariation)
    {
        $extract = TranslationAPIFacade::getInstance()->__('Add Highlight', 'poptheme-wassup');
        $titles = array(
            self::MODULE_BUTTONINNER_HIGHLIGHT_CREATE => $extract,
            self::MODULE_BUTTONINNER_HIGHLIGHT_CREATEBTN => $extract,
        );
        if ($title = $titles[$componentVariation[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($componentVariation);
    }
}


