<?php
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_LocationPosts_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD = 'dataload-locationposts-typeahead';
    public final const MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR = 'dataload-locationposts-scroll-navigator';
    public final const MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS = 'dataload-locationposts-scroll-addons';
    public final const MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS = 'dataload-locationposts-scroll-details';
    public final const MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS = 'dataload-authorlocationposts-scroll-details';
    public final const MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS = 'dataload-taglocationposts-scroll-details';
    public final const MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW = 'dataload-locationposts-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW = 'dataload-authorlocationposts-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW = 'dataload-taglocationposts-scroll-simpleview';
    public final const MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW = 'dataload-locationposts-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW = 'dataload-authorlocationposts-scroll-fullview';
    public final const MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW = 'dataload-taglocationposts-scroll-fullview';
    public final const MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL = 'dataload-locationposts-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL = 'dataload-authorlocationposts-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL = 'dataload-taglocationposts-scroll-thumbnail';
    public final const MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST = 'dataload-locationposts-scroll-list';
    public final const MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST = 'dataload-authorlocationposts-scroll-list';
    public final const MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST = 'dataload-taglocationposts-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],

            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST],

            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_TYPEAHEAD => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
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
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::COMPONENT_LAYOUTPOST_TYPEAHEAD_COMPONENT],

            /*********************************************
             * Post Scrolls
             *********************************************/

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Common blocks (Home/Page/Author/Single)
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_NAVIGATOR],
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Home/Page blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_DETAILS],
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_FULLVIEW],
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Author blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_DETAILS],
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_AUTHORLOCATIONPOSTS_FULLVIEW],
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Tag blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_DETAILS],
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_FULLVIEW],
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_LIST:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::COMPONENT_FILTER_LOCATIONPOSTS];

            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORLOCATIONPOSTS];

            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGLOCATIONPOSTS];
        }

        return parent::getFilterSubmodule($component);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
    //         case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
    //         case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
    //             return UserRequestNature::USER;

    //         case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
    //         case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
    //         case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
    //         case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
    //         case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($component);
    // }

    public function getFormat(array $component): ?string
    {
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOSTS_TYPEAHEAD],
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
        } elseif (in_array($component, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
         // Filter by the Profile/Community
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                return $this->instanceManager->getInstance(LocationPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', PoP_LocationPosts_PostNameUtils::getNamesLc());
                break;
        }

        parent::initModelProps($component, $props);
    }
}



