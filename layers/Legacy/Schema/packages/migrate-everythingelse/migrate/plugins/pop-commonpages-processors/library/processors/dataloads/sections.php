<?php
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_Custom_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS = 'dataload-whoweare-scroll-details';
    public final const MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL = 'dataload-whoweare-scroll-thumbnail';
    public final const MODULE_DATALOAD_WHOWEARE_SCROLL_LIST = 'dataload-whoweare-scroll-list';
    public final const MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW = 'dataload-whoweare-scroll-fullview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_DETAILS],
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_THUMBNAIL],
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_LIST],
            self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::MODULE_SCROLL_WHOWEARE_FULLVIEW],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function getDatasource(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
        }

        return parent::getDatasource($componentVariation, $props);
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return getWhoweareCoreUserIds();
        }

        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }
}



