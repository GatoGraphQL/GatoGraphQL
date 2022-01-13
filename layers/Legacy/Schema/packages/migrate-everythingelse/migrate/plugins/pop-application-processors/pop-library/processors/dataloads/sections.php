<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS = 'dataload-singleauthors-scroll-details';
    public const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW = 'dataload-singleauthors-scroll-fullview';
    public const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL = 'dataload-singleauthors-scroll-thumbnail';
    public const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST = 'dataload-singleauthors-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL => POP_ROUTE_AUTHORS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    // // Single Authors has no filter, because the authors are provided using 'include' which can't be filtered
    // function getFilterSubmodule(array $module) {

    //     switch ($module[1]) {

    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:

    //             return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
    //     }

    //     return parent::getFilterSubmodule($module);
    // }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
        );
        if (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsSingleauthors($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



