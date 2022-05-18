<?php

class PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST = 'sidebarmulticomponent-automatedemails-post';
    public final const MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE = 'sidebarmulticomponent-automatedemails-featuredimage';
    public final const MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER = 'sidebarmulticomponent-automatedemails-featuredimagevolunteer';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_AE_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_AE_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER];
                $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG];
                break;
        }

        return $ret;
    }
}



