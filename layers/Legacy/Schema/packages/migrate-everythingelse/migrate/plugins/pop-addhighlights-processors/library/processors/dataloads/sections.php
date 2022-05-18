<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPSchema\Highlights\TypeResolvers\ObjectType\HighlightObjectTypeResolver;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_AddHighlights_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW = 'dataload-authorhighlights-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST = 'dataload-authorhighlights-scroll-list';
    public final const MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL = 'dataload-authorhighlights-scroll-thumbnail';
    public final const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS = 'dataload-highlights-scroll-addons';
    public final const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW = 'dataload-highlights-scroll-fullview';
    public final const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST = 'dataload-highlights-scroll-list';
    public final const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR = 'dataload-highlights-scroll-navigator';
    public final const MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL = 'dataload-highlights-scroll-thumbnail';
    public final const MODULE_DATALOAD_HIGHLIGHTS_TYPEAHEAD = 'dataload-highlights-typeahead';
    public final const MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW = 'dataload-singlerelatedhighlightcontent-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL = 'dataload-singlerelatedhighlightcontent-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST = 'dataload-singlerelatedhighlightcontent-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR],
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS],
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_HIGHLIGHTS_TYPEAHEAD => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_HIGHLIGHTS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::COMPONENT_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_NAVIGATOR],
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_ADDONS],
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_FULLVIEW],
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_LIST],
            self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_AUTHORHIGHLIGHTS_FULLVIEW],
            self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_LIST],
            self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_FULLVIEW],
            self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_HIGHLIGHTS_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                return [PoP_AddHighlights_Module_Processor_CustomFilters::class, PoP_AddHighlights_Module_Processor_CustomFilters::COMPONENT_FILTER_HIGHLIGHTS];

            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
                return [PoP_AddHighlights_Module_Processor_CustomFilters::class, PoP_AddHighlights_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORHIGHLIGHTS];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {

        // Add the format attr
        $navigators = array(
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::COMPONENT_DATALOAD_HIGHLIGHTS_TYPEAHEAD],
        );
        if (in_array($component, $navigators)) {
            $format = POP_FORMAT_NAVIGATOR;
        } elseif (in_array($component, $addons)) {
            $format = POP_FORMAT_ADDONS;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($component, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        }

        return $format ?? parent::getFormat($component);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
    //             return UserRequestNature::USER;

    //         case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                PoP_AddHighlights_Module_Processor_SectionBlocksUtils::addDataloadqueryargsSinglehighlights($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                return $this->instanceManager->getInstance(HighlightObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('highlights', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



