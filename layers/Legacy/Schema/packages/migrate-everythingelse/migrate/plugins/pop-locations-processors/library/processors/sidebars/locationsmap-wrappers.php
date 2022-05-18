<?php

class GD_EM_Module_Processor_LocationMapConditionWrappers extends GD_EM_Module_Processor_LocationMapConditionWrappersBase
{
    public final const MODULE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP = 'em-layoutwrapper-postlocationsmap';
    public final const MODULE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP = 'em-layoutwrapper-userlocationsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP],
            [self::class, self::MODULE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP],
        );
    }

    public function getLocationlinksTemplate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP:
                return [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS];
            ;

            case self::MODULE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP:
                return [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS];
        }
        
        return parent::getLocationlinksTemplate($module);
    }
}



