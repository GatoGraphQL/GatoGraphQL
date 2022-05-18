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

    public function getComponentVariationsToProcess(): array
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

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
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
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
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

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
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

        return parent::getFilterSubmodule($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
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
        if (in_array($componentVariation, $navigators)) {
            $format = POP_FORMAT_NAVIGATOR;
        } elseif (in_array($componentVariation, $addons)) {
            $format = POP_FORMAT_ADDONS;
        } elseif (in_array($componentVariation, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($componentVariation, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($componentVariation, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($componentVariation, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    // public function getNature(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($componentVariation);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
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

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
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
                return $this->instanceManager->getInstance(HighlightObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
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

        parent::initModelProps($componentVariation, $props);
    }
}



