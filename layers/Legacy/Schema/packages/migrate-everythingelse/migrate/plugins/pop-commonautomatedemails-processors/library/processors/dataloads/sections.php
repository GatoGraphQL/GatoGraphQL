<?php
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class PoPTheme_Wassup_AE_Module_Processor_SectionDataloads extends PoP_CommonAutomatedEmails_Module_Processor_SectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS = 'dataload-automatedemails-latestcontent-scroll-details';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW = 'dataload-automatedemails-latestcontent-scroll-simpleview';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW = 'dataload-automatedemails-latestcontent-scroll-fullview';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL = 'dataload-automatedemails-latestcontent-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST = 'dataload-automatedemails-latestcontent-scroll-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(

            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST => [PoPTheme_Wassup_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {

        // Add the format attr
        $details = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS,
        );
        $simpleviews = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW,
        );
        $fullviews = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW,
        );
        $thumbnails = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL,
        );
        $lists = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST,
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontent($ret);

                // Return the posts created after the given timestamp
                $start_date = strtotime("-7 day", ComponentModelModuleInfo::get('time'));
                // $ret['date-query'] = array(
                //     array(
                //         'after' => date('Y-m-d H:i:s', $start_date),
                //         'inclusive' => true,
                //     )
                // );
                $ret['date-from-inclusive'] = date('Y-m-d H:i:s', $start_date);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}


