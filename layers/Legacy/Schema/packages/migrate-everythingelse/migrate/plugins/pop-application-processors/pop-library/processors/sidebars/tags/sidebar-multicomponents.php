<?php

class GD_Custom_Module_Processor_TagMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_SIDEBARMULTICOMPONENT_TAGLEFT = 'sidebarmulticomponent-tagleft';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_TAGRIGHT = 'sidebarmulticomponent-tagright';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SIDEBARMULTICOMPONENT_TAGLEFT,
            self::COMPONENT_SIDEBARMULTICOMPONENT_TAGRIGHT,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_SIDEBARMULTICOMPONENT_TAGLEFT:
                $ret[] = [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_TAGSOCIALMEDIA];
                break;

            case self::COMPONENT_SIDEBARMULTICOMPONENT_TAGRIGHT:
                $ret[] = [GD_Custom_Module_Processor_TagWidgets::class, GD_Custom_Module_Processor_TagWidgets::COMPONENT_WIDGETCOMPACT_TAGINFO];
                break;
        }

        return $ret;
    }
}



