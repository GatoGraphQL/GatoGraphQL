<?php

class GD_Custom_Module_Processor_TagMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_TAGLEFT = 'sidebarmulticomponent-tagleft';
    public final const MODULE_SIDEBARMULTICOMPONENT_TAGRIGHT = 'sidebarmulticomponent-tagright';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_TAGLEFT],
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_TAGRIGHT],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
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



