<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts extends PoP_Module_Processor_BareSimpleViewPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW = 'layout-automatedemails-previewpost-event-simpleview';
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW],
        );
    }


    public function getAuthorComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorComponent($component);
    }

    public function getAbovecontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getAbovecontentSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW:
                $ret[] = [GD_EM_Module_Processor_EventMultipleComponents::class, GD_EM_Module_Processor_EventMultipleComponents::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATION];
                break;
        }

        return $ret;
    }

    public function getAftercontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getAftercontentSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW:
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::class, PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::COMPONENT_QUICKLINKGROUP_EVENTBOTTOM];
                break;
        }

        return $ret;
    }
}


