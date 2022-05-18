<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class PoP_ContentCreation_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public final const MODULE_DATALOAD_MYCONTENT_TABLE_EDIT = 'dataload-mycontent-table-edit';
    public final const MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-mycontent-scroll-simpleviewpreview';
    public final const MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW = 'dataload-mycontent-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT => [PoP_Module_Processor_Tables::class, PoP_Module_Processor_Tables::MODULE_TABLE_MYCONTENT],
            self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_FULLVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_MYCONTENT];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $tables = array(
            [self::class, self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
        );
        if (in_array($module, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        }

        return $format ?? parent::getFormat($module);
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('access your content', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($module, $props);
    }
}



