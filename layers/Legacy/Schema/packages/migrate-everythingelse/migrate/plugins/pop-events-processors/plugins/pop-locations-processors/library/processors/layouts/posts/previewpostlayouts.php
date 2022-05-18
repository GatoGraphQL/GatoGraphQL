<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS = 'layout-previewpost-event-mapdetails';
    public final const MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS = 'layout-previewpost-event-horizontalmapdetails';
    public final const MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS = 'layout-previewost-pastevent-mapdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getBelowthumbLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getBottomSubmodules(array $component)
    {
        $ret = parent::getBottomSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_XSMALL];
        }

        return parent::getPostThumbSubmodule($component);
    }

    public function authorPositions(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT,
                );
        }

        return parent::authorPositions($component);
    }

    public function getTitleBeforeauthors(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('posted by', 'poptheme-wassup')
                );
        }

        return parent::getTitleBeforeauthors($component, $props);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }
    

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                $ret[GD_JS_CLASSES]['authors-belowcontent'] = 'pull-right';
                break;
        }

        return $ret;
    }
}


