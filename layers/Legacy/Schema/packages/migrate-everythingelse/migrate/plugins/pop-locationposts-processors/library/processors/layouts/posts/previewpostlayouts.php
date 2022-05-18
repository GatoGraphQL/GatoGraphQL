<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR = 'layout-previewpost-locationpost-navigator';
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS = 'layout-previewpost-locationpost-addons';
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS = 'layout-previewpost-locationpost-details';
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL = 'layout-previewpost-locationpost-thumbnail';
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST = 'layout-previewpost-locationpost-list';
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS = 'layout-previewpost-locationpost-mapdetails';
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS = 'layout-previewpost-locationpost-horizontalmapdetails';
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED = 'layout-previewpost-locationpost-related';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED],
        );
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
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

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getBottomSubmodules(array $component)
    {
        $ret = parent::getBottomSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubmodules($component)
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

    public function getBelowthumbLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($component);

        switch ($component[1]) {
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

    public function getPostThumbSubmodule(array $component)
    {
        switch ($component[1]) {
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

        return parent::getPostThumbSubmodule($component);
    }

    public function showExcerpt(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function authorPositions(array $component)
    {
        switch ($component[1]) {
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

    public function horizontalLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }

    public function getTitleBeforeauthors(array $component, array &$props)
    {
        switch ($component[1]) {
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

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
                // case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:

                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowavatar';
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        switch ($component[1]) {
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


