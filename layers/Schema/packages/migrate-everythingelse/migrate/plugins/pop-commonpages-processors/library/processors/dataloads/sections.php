<?php
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class GD_Custom_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public const MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS = 'dataload-whoweare-scroll-details';
    public const MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL = 'dataload-whoweare-scroll-thumbnail';
    public const MODULE_DATALOAD_WHOWEARE_SCROLL_LIST = 'dataload-whoweare-scroll-list';
    public const MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW = 'dataload-whoweare-scroll-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_DETAILS],
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_THUMBNAIL],
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_LIST],
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_FULLVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getDatasource(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
        }

        return parent::getDatasource($module, $props);
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return getWhoweareCoreUserIds();
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }
}



