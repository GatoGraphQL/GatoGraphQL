<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_LocationViewComponentButtonInners extends PoP_Module_Processor_LocationViewComponentButtonInnersBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS = 'viewcomponent-buttoninner-locations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],        );
    }

    public function getBtnTitle(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS:

                return TranslationAPIFacade::getInstance()->__('Locations', 'em-popprocessors');
        }
        
        return parent::getBtnTitle($component);
    }

    public function getLocationModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS:
                return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::COMPONENT_EM_LAYOUT_LOCATIONNAME];
        }
        
        return parent::getLocationModule($component);
    }
}


