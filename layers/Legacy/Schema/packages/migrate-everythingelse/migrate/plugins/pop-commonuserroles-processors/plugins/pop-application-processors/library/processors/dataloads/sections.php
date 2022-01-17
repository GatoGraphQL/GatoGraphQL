<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_URE_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_ORGANIZATIONS_TYPEAHEAD = 'dataload-organizations-typeahead';
    public const MODULE_DATALOAD_INDIVIDUALS_TYPEAHEAD = 'dataload-individuals-typeahead';
    public const MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR = 'dataload-organizations-scroll-navigator';
    public const MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR = 'dataload-individuals-scroll-navigator';
    public const MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS = 'dataload-organizations-scroll-addons';
    public const MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS = 'dataload-individuals-scroll-addons';
    public const MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS = 'dataload-organizations-scroll-details';
    public const MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS = 'dataload-individuals-scroll-details';
    public const MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW = 'dataload-organizations-scroll-fullview';
    public const MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW = 'dataload-individuals-scroll-fullview';
    public const MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL = 'dataload-organizations-scroll-thumbnail';
    public const MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL = 'dataload-individuals-scroll-thumbnail';
    public const MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST = 'dataload-organizations-scroll-list';
    public const MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST = 'dataload-individuals-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_DATALOAD_INDIVIDUALS_TYPEAHEAD => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_DATALOAD_ORGANIZATIONS_TYPEAHEAD => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
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
            self::MODULE_DATALOAD_ORGANIZATIONS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_INDIVIDUALS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Common blocks (Home/Page/Author/Single)
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_NAVIGATOR],
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_NAVIGATOR],

            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_ADDONS],
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Home/Page blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::MODULE_SCROLL_ORGANIZATIONS_DETAILS],
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::MODULE_SCROLL_INDIVIDUALS_DETAILS],

            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::MODULE_SCROLL_ORGANIZATIONS_FULLVIEW],
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW => [GD_URE_Module_Processor_CustomScrolls::class, GD_URE_Module_Processor_CustomScrolls::MODULE_SCROLL_INDIVIDUALS_FULLVIEW],

            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_THUMBNAIL],
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_THUMBNAIL],

            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_LIST],
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USER_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_INDIVIDUALS_TYPEAHEAD:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::MODULE_FILTER_INDIVIDUALS];

            case self::MODULE_DATALOAD_ORGANIZATIONS_TYPEAHEAD:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::MODULE_FILTER_ORGANIZATIONS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {
        $details = array(
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST],
        );
        if (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($module);
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_ORGANIZATIONS_TYPEAHEAD:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
                $ret['role'] = GD_URE_ROLE_ORGANIZATION;
                break;

            case self::MODULE_DATALOAD_INDIVIDUALS_TYPEAHEAD:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                $ret['role'] = GD_URE_ROLE_INDIVIDUAL;
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_ORGANIZATIONS_TYPEAHEAD:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
            case self::MODULE_DATALOAD_INDIVIDUALS_TYPEAHEAD:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('organizations', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('individuals', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($module, $props);
    }
}



