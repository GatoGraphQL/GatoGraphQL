<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

class PoP_ContentPostLinksCreation_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public final const MODULE_DATALOAD_MYLINKS_TABLE_EDIT = 'dataload-mylinks-table-edit';
    public final const MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-mylinks-scroll-simpleviewpreview';
    public final const MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW = 'dataload-mylinks-scroll-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT => POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

        /*********************************************
         * My Content Tables
         *********************************************/
            self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT => [PoP_ContentPostLinksCreation_Module_Processor_Tables::class, PoP_ContentPostLinksCreation_Module_Processor_Tables::MODULE_TABLE_MYLINKS],

        /*********************************************
         * My Content Full Post Previews
         *********************************************/
            self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_CustomScrolls::class, PoP_ContentPostLinksCreation_Module_Processor_CustomScrolls::MODULE_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW],
            self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_CustomScrolls::class, PoP_ContentPostLinksCreation_Module_Processor_CustomScrolls::MODULE_SCROLL_MYLINKS_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_ContentPostLinksCreation_Module_Processor_CustomFilters::class, PoP_ContentPostLinksCreation_Module_Processor_CustomFilters::MODULE_FILTER_MYLINKS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $tables = array(
            [self::class, self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW],
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
            case self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW:
                $ret['categories'] = [POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS];
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYLINKS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYLINKS_SCROLL_FULLVIEWPREVIEW:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('links', 'poptheme-wassup'));
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('access your links', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($module, $props);
    }
}



