<?php

class GD_EM_Module_Processor_LocationMapConditionWrappers extends GD_EM_Module_Processor_LocationMapConditionWrappersBase
{
    public final const COMPONENT_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP = 'em-layoutwrapper-postlocationsmap';
    public final const COMPONENT_EM_LAYOUTWRAPPER_USERLOCATIONSMAP = 'em-layoutwrapper-userlocationsmap';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP,
            self::COMPONENT_EM_LAYOUTWRAPPER_USERLOCATIONSMAP,
        );
    }

    public function getLocationlinksTemplate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP:
                return [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS];
            ;

            case self::COMPONENT_EM_LAYOUTWRAPPER_USERLOCATIONSMAP:
                return [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS];
        }
        
        return parent::getLocationlinksTemplate($component);
    }
}



