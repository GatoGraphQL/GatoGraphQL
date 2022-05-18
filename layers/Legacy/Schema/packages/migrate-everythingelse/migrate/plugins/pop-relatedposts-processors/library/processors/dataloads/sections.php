<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class PoP_RelatedPosts_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS = 'dataload-singlerelatedcontent-scroll-details';
    public final const MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW = 'dataload-singlerelatedcontent-scroll-simpleview';
    public final const MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW = 'dataload-singlerelatedcontent-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL = 'dataload-singlerelatedcontent-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST = 'dataload-singlerelatedcontent-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL => POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_CONTENT_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_SINGLERELATEDCONTENT_FULLVIEW],
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_CONTENT_DETAILS],
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_CONTENT_THUMBNAIL],
            self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_CONTENT_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($component);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS:
    //         case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
    //         case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST:
                PoP_RelatedPosts_SectionUtils::addDataloadqueryargsReferences($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('results', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



