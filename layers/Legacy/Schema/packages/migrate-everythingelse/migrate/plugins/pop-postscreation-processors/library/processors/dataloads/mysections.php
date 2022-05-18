<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

class PoP_PostsCreation_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public final const MODULE_DATALOAD_MYPOSTS_TABLE_EDIT = 'dataload-myposts-table-edit';
    public final const MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-myposts-scroll-simpleviewpreview';
    public final const MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW = 'dataload-myposts-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT => POP_POSTSCREATION_ROUTE_MYPOSTS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT => [PoP_Module_Processor_Tables::class, PoP_Module_Processor_Tables::MODULE_TABLE_MYPOSTS],
            self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_MYPOSTS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $tables = array(
            [self::class, self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
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
            case self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                if ($categories = gdDataloadAllcontentCategories()) {
                    $ret['category-in'] = $categories;
                }
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('posts', 'poptheme-wassup'));
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('access your posts', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($module, $props);
    }
}



