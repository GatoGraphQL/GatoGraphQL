<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

class PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_LINKS_TYPEAHEAD = 'dataload-links-typeahead';
    public const MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR = 'dataload-links-scroll-navigator';
    public const MODULE_DATALOAD_LINKS_SCROLL_ADDONS = 'dataload-links-scroll-addons';
    public const MODULE_DATALOAD_LINKS_SCROLL_DETAILS = 'dataload-links-scroll-details';
    public const MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS = 'dataload-authorlinks-scroll-details';
    public const MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS = 'dataload-taglinks-scroll-details';
    public const MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW = 'dataload-links-scroll-simpleview';
    public const MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW = 'dataload-authorlinks-scroll-simpleview';
    public const MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW = 'dataload-taglinks-scroll-simpleview';
    public const MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW = 'dataload-links-scroll-fullview';
    public const MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW = 'dataload-authorlinks-scroll-fullview';
    public const MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW = 'dataload-taglinks-scroll-fullview';
    public const MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL = 'dataload-links-scroll-thumbnail';
    public const MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL = 'dataload-authorlinks-scroll-thumbnail';
    public const MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL = 'dataload-taglinks-scroll-thumbnail';
    public const MODULE_DATALOAD_LINKS_SCROLL_LIST = 'dataload-links-scroll-list';
    public const MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST = 'dataload-authorlinks-scroll-list';
    public const MODULE_DATALOAD_TAGLINKS_SCROLL_LIST = 'dataload-taglinks-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LINKS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_LINKS_SCROLL_ADDONS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_LINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_LINKS_TYPEAHEAD => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL => POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            /*********************************************
             * Typeaheads
             *********************************************/
            // Straight to the layout
            self::MODULE_DATALOAD_LINKS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Common blocks (Home/Page/Author/Single)
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_NAVIGATOR],
            self::MODULE_DATALOAD_LINKS_SCROLL_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Home/Page blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_DETAILS],
            self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_SIMPLEVIEW],
            self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_FULLVIEW],
            self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_THUMBNAIL],
            self::MODULE_DATALOAD_LINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Author blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_DETAILS],
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORLINKS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Tag blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_DETAILS],
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_FULLVIEW],
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrolls::class, PoP_ContentPostLinks_Module_Processor_CustomScrolls::MODULE_SCROLL_LINKS_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LINKS_TYPEAHEAD:
            case self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_LINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_CustomFilters::class, PoP_ContentPostLinks_Module_Processor_CustomFilters::MODULE_FILTER_LINKS];

            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST:
                return [PoP_ContentPostLinks_Module_Processor_CustomFilters::class, PoP_ContentPostLinks_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORLINKS];

            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST:
                return [self::class, self::MODULE_FILTER_TAGLINKS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $navigators = array(
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_ADDONS],
        );
        $details = array(
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_LINKS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_LINKS_TYPEAHEAD],
        );
        if (in_array($module, $navigators)) {
            $format = POP_FORMAT_NAVIGATOR;
        } elseif (in_array($module, $addons)) {
            $format = POP_FORMAT_ADDONS;
        } elseif (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
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
    //         case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_LINKS_TYPEAHEAD:
            case self::MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_LINKS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_LINKS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST:
                $ret['categories'] = [POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS];
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
         // Filter by the Profile/Community
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LINKS_TYPEAHEAD:
            case self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_LINKS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_LINKS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LINKS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_LINKS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_LINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_LINKS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLINKS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('links', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



