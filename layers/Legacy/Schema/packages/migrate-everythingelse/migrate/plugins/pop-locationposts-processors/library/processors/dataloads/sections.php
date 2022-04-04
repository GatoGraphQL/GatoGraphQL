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

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
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
            self::MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],

            /*********************************************
             * Post Scrolls
             *********************************************/

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Common blocks (Home/Page/Author/Single)
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_NAVIGATOR],
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Home/Page blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_DETAILS],
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_THUMBNAIL],
            self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Author blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORLOCATIONPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_LIST],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
            * Tag blocks
            *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_DETAILS],
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST => [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::MODULE_FILTER_LOCATIONPOSTS];

            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORLOCATIONPOSTS];

            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::MODULE_FILTER_TAGLOCATIONPOSTS];
        }

        return parent::getFilterSubmodule($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($module);
    // }

    public function getFormat(array $module): ?string
    {
        $details = array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD],
        );
        if (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
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

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
         // Filter by the Profile/Community
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                return $this->instanceManager->getInstance(LocationPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', PoP_LocationPosts_PostNameUtils::getNamesLc());
                break;
        }

        parent::initModelProps($module, $props);
    }
}



