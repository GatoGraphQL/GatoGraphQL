<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

class PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_LINKS_TYPEAHEAD = 'dataload-links-typeahead';
    public final const MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR = 'dataload-links-scroll-navigator';
    public final const MODULE_DATALOAD_LINKS_SCROLL_ADDONS = 'dataload-links-scroll-addons';
    public final const MODULE_DATALOAD_LINKS_SCROLL_DETAILS = 'dataload-links-scroll-details';
    public final const MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS = 'dataload-authorlinks-scroll-details';
    public final const MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS = 'dataload-taglinks-scroll-details';
    public final const MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW = 'dataload-links-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW = 'dataload-authorlinks-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW = 'dataload-taglinks-scroll-simpleview';
    public final const MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW = 'dataload-links-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW = 'dataload-authorlinks-scroll-fullview';
    public final const MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW = 'dataload-taglinks-scroll-fullview';
    public final const MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL = 'dataload-links-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL = 'dataload-authorlinks-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL = 'dataload-taglinks-scroll-thumbnail';
    public final const MODULE_DATALOAD_LINKS_SCROLL_LIST = 'dataload-links-scroll-list';
    public final const MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST = 'dataload-authorlinks-scroll-list';
    public final const MODULE_DATALOAD_TAGLINKS_SCROLL_LIST = 'dataload-taglinks-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_NAVIGATOR],
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS],
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_LINKS_TYPEAHEAD => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(

            /*********************************************
             * Typeaheads
             *********************************************/
            // Straight to the layout
            self::COMPONENT_DATALOAD_LINKS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::COMPONENT_LAYOUTPOST_TYPEAHEAD_COMPONENT],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Common blocks (Home/Page/Author/Single)
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::COMPONENT_DATALOAD_LINKS_SCROLL_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_NAVIGATOR],
            self::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Home/Page blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_DETAILS],
            self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_FULLVIEW],
            self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_THUMBNAIL],
            self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Author blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_DETAILS],
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_AUTHORLINKS_FULLVIEW],
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_THUMBNAIL],
            self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Tag blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_DETAILS],
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_FULLVIEW],
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_THUMBNAIL],
            self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LINKS_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LINKS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_CustomFilters::class, PoP_ContentPostLinks_Module_Processor_CustomFilters::COMPONENT_FILTER_LINKS];

            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_CustomFilters::class, PoP_ContentPostLinks_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORLINKS];

            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST:
                return [self::class, self::COMPONENT_FILTER_TAGLINKS];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {

        // Add the format attr
        $navigators = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS],
        );
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::COMPONENT_DATALOAD_LINKS_TYPEAHEAD],
        );
        if (in_array($component, $navigators)) {
            $format = POP_FORMAT_NAVIGATOR;
        } elseif (in_array($component, $addons)) {
            $format = POP_FORMAT_ADDONS;
        } elseif (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
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
    //         case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
    //         case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
    //         case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST:
    //             return UserRequestNature::USER;

    //         case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS:
    //         case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
    //         case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LINKS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST:
                $ret['categories'] = [POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS];
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
         // Filter by the Profile/Community
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LINKS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_LINKS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLINKS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('links', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



