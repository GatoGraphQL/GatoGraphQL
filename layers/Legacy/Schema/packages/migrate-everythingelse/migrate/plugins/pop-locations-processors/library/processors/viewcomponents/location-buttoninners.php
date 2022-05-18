<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_LocationViewComponentButtonInners extends PoP_Module_Processor_LocationViewComponentButtonInnersBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS = 'viewcomponent-buttoninner-locations';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],        );
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS:

                return TranslationAPIFacade::getInstance()->__('Locations', 'em-popprocessors');
        }
        
        return parent::getBtnTitle($module);
    }

    public function getLocationModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS:
                return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::MODULE_EM_LAYOUT_LOCATIONNAME];
        }
        
        return parent::getLocationModule($module);
    }
}


