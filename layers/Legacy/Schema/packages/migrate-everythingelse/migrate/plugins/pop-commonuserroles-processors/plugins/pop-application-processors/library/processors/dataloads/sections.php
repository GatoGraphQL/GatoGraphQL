<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_URE_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD = 'dataload-organizations-typeahead';
    public final const COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD = 'dataload-individuals-typeahead';
    public final const COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR = 'dataload-organizations-scroll-navigator';
    public final const COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR = 'dataload-individuals-scroll-navigator';
    public final const COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS = 'dataload-organizations-scroll-addons';
    public final const COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_ADDONS = 'dataload-individuals-scroll-addons';
    public final const COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS = 'dataload-organizations-scroll-details';
    public final const COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS = 'dataload-individuals-scroll-details';
    public final const COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW = 'dataload-organizations-scroll-fullview';
    public final const COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW = 'dataload-individuals-scroll-fullview';
    public final const COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL = 'dataload-organizations-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL = 'dataload-individuals-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST = 'dataload-organizations-scroll-list';
    public final const COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST = 'dataload-individuals-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD],
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR],
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_ADDONS],
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_ADDONS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(array $component)
    {
        $inner_components = array(

            /*********************************************
         * Typeaheads
         *********************************************/
            // Straight to the layout
            self::COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Common blocks (Home/Page/Author/Single)
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_NAVIGATOR],
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_NAVIGATOR],

            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_ADDONS],
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Home/Page blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::COMPONENT_SCROLL_ORGANIZATIONS_DETAILS],
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::COMPONENT_SCROLL_INDIVIDUALS_DETAILS],

            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::COMPONENT_SCROLL_ORGANIZATIONS_FULLVIEW],
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::COMPONENT_SCROLL_INDIVIDUALS_FULLVIEW],

            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_THUMBNAIL],
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_THUMBNAIL],

            self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_LIST],
            self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_USER_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubcomponent(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::COMPONENT_FILTER_INDIVIDUALS];

            case self::COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::COMPONENT_FILTER_ORGANIZATIONS];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(array $component): ?string
    {
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
            [self::class, self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST],
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
                $ret['role'] = GD_URE_ROLE_ORGANIZATION;
                break;

            case self::COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                $ret['role'] = GD_URE_ROLE_INDIVIDUAL;
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_TYPEAHEAD:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('organizations', 'poptheme-wassup'));
                break;

            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_ADDONS:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('individuals', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($component, $props);
    }
}



