<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR = 'layout-previewpost-locationpost-navigator';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS = 'layout-previewpost-locationpost-addons';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS = 'layout-previewpost-locationpost-details';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL = 'layout-previewpost-locationpost-thumbnail';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST = 'layout-previewpost-locationpost-list';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS = 'layout-previewpost-locationpost-mapdetails';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS = 'layout-previewpost-locationpost-horizontalmapdetails';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED = 'layout-previewpost-locationpost-related';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR,
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS,
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS,
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL,
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST,
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS,
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS,
            self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED,
        );
    }

    public function getQuicklinkgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER];
        }

        return parent::getQuicklinkgroupBottomSubcomponent($component);
    }

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
                // case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
                // return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTVOLUNTEER];
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getBottomSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBottomSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubcomponents($component)
                );
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                }
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getBelowthumbLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowthumbLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_XSMALL];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER];
        }

        return parent::getPostThumbSubcomponent($component);
    }

    public function showExcerpt(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function getTitleHtmlmarkup(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function authorPositions(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT,
                );
        }

        return parent::authorPositions($component);
    }

    public function horizontalLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }

    public function getTitleBeforeauthors(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('posted by', 'pop-locationposts-processors')
                );
        }

        return parent::getTitleBeforeauthors($component, $props);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
                // case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:

                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowavatar';
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                $ret[GD_JS_CLASSES]['authors-belowcontent'] = 'pull-right';
                break;
        }

        return $ret;
    }
}


