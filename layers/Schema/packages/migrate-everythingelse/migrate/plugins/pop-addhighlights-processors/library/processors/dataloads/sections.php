<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Highlights\TypeResolvers\HighlightTypeResolver;

class PoP_AddHighlights_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW = 'dataload-authorhighlights-scroll-fullview';
    public const MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST = 'dataload-authorhighlights-scroll-list';
    public const MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL = 'dataload-authorhighlights-scroll-thumbnail';
    public const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS = 'dataload-highlights-scroll-addons';
    public const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW = 'dataload-highlights-scroll-fullview';
    public const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST = 'dataload-highlights-scroll-list';
    public const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR = 'dataload-highlights-scroll-navigator';
    public const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL = 'dataload-highlights-scroll-thumbnail';
    public const MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD = 'dataload-highlights-typeahead';
    public const MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW = 'dataload-singlerelatedhighlightcontent-scroll-fullview';
    public const MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL = 'dataload-singlerelatedhighlightcontent-scroll-thumbnail';
    public const MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST = 'dataload-singlerelatedhighlightcontent-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_NAVIGATOR],
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_ADDONS],
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_FULLVIEW],
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_THUMBNAIL],
            self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_LIST],
            self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORHIGHLIGHTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_LIST],
            self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_FULLVIEW],
            self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_HIGHLIGHTS_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                return [PoP_AddHighlights_Module_Processor_CustomFilters::class, PoP_AddHighlights_Module_Processor_CustomFilters::MODULE_FILTER_HIGHLIGHTS];

            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
                return [PoP_AddHighlights_Module_Processor_CustomFilters::class, PoP_AddHighlights_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORHIGHLIGHTS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $navigators = array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD],
        );
        if (in_array($module, $navigators)) {
            $format = POP_FORMAT_NAVIGATOR;
        } elseif (in_array($module, $addons)) {
            $format = POP_FORMAT_ADDONS;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($module, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
    //             return UserRouteNatures::USER;

    //         case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                PoP_AddHighlights_Module_Processor_SectionBlocksUtils::addDataloadqueryargsSinglehighlights($ret);
                break;
        }

        return $ret;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                return HighlightTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('highlights', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



