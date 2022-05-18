<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS = 'layout-previewpost-event-mapdetails';
    public final const MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS = 'layout-previewpost-event-horizontalmapdetails';
    public final const MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS = 'layout-previewost-pastevent-mapdetails';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($module);
    }

    public function getBelowthumbLayoutSubmodules(array $module)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getBottomSubmodules(array $module)
    {
        $ret = parent::getBottomSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];

            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER];

            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_XSMALL];
        }

        return parent::getPostThumbSubmodule($module);
    }

    public function authorPositions(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT,
                );
        }

        return parent::authorPositions($module);
    }

    public function getTitleBeforeauthors(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('posted by', 'poptheme-wassup')
                );
        }

        return parent::getTitleBeforeauthors($module, $props);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }
    

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS:
                $ret[GD_JS_CLASSES]['authors-belowcontent'] = 'pull-right';
                break;
        }

        return $ret;
    }
}


