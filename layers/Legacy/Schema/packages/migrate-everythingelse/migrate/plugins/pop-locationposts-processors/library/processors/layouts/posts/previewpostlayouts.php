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

    public function getComponentVariationsToProcess(): array
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

    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER];
        }

        return parent::getQuicklinkgroupBottomSubmodule($componentVariation);
    }

    public function getQuicklinkgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
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

        return parent::getQuicklinkgroupTopSubmodule($componentVariation);
    }

    public function getBottomSubmodules(array $componentVariation)
    {
        $ret = parent::getBottomSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubmodules($componentVariation)
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

    public function getBelowthumbLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
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

    public function getPostThumbSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
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

        return parent::getPostThumbSubmodule($componentVariation);
    }

    public function showExcerpt(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::showExcerpt($componentVariation);
    }

    public function getTitleHtmlmarkup(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($componentVariation, $props);
    }

    public function authorPositions(array $componentVariation)
    {
        switch ($componentVariation[1]) {
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

        return parent::authorPositions($componentVariation);
    }

    public function horizontalLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($componentVariation);
    }

    public function horizontalMediaLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($componentVariation);
    }

    public function getTitleBeforeauthors(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
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

        return parent::getTitleBeforeauthors($componentVariation, $props);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS:
                // case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_RELATED:

                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowavatar';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        switch ($componentVariation[1]) {
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


