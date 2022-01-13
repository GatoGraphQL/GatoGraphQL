<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public const MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP = 'dataload-singleauthors-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP => POP_ROUTE_AUTHORS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSections::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SINGLEAUTHORS_SCROLLMAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        );
        if (in_array($module, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP:
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsSingleauthors($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($module, $props);
    }
}



