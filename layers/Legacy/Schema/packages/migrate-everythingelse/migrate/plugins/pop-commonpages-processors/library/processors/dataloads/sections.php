<?php
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_Custom_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS = 'dataload-whoweare-scroll-details';
    public final const COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL = 'dataload-whoweare-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST = 'dataload-whoweare-scroll-list';
    public final const COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW = 'dataload-whoweare-scroll-fullview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS,
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL,
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST,
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::COMPONENT_SCROLL_WHOWEARE_DETAILS],
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::COMPONENT_SCROLL_WHOWEARE_THUMBNAIL],
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::COMPONENT_SCROLL_WHOWEARE_LIST],
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW => [GD_Custom_Module_Processor_CustomScrolls::class, GD_Custom_Module_Processor_CustomScrolls::COMPONENT_SCROLL_WHOWEARE_FULLVIEW],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getDatasource(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
        }

        return parent::getDatasource($component, $props);
    }

    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST:
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW:
                return getWhoweareCoreUserIds();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }
}



