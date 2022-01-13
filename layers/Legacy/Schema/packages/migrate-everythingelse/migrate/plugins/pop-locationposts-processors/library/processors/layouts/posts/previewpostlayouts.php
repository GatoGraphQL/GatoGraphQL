<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR = 'layout-previewpost-locationpost-navigator';
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS = 'layout-previewpost-locationpost-addons';
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS = 'layout-previewpost-locationpost-details';
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL = 'layout-previewpost-locationpost-thumbnail';
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST = 'layout-previewpost-locationpost-list';
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS = 'layout-previewpost-locationpost-mapdetails';
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS = 'layout-previewpost-locationpost-horizontalmapdetails';
    public const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED = 'layout-previewpost-locationpost-related';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED],
        );
    }

    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
                // case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
                // return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTVOLUNTEER];
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($module);
    }

    public function getBottomSubmodules(array $module)
    {
        $ret = parent::getBottomSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubmodules($module)
                );
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getBelowthumbLayoutSubmodules(array $module)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_XSMALL];

            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER];

            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER];
        }

        return parent::getPostThumbSubmodule($module);
    }

    public function showExcerpt(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::showExcerpt($module);
    }

    public function getTitleHtmlmarkup(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($module, $props);
    }

    public function authorPositions(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT,
                );
        }

        return parent::authorPositions($module);
    }

    public function horizontalLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($module);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }

    public function getTitleBeforeauthors(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('posted by', 'pop-locationposts-processors')
                );
        }

        return parent::getTitleBeforeauthors($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
                // case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:

                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowavatar';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
                $ret[GD_JS_CLASSES]['authors-belowcontent'] = 'pull-right';
                break;
        }

        return $ret;
    }
}


