<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

class PoP_PostsCreation_Module_Processor_MySectionDataloads extends PoP_Module_Processor_MySectionDataloadsBase
{
    public final const MODULE_DATALOAD_MYPOSTS_TABLE_EDIT = 'dataload-myposts-table-edit';
    public final const MODULE_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-myposts-scroll-simpleviewpreview';
    public final const MODULE_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW = 'dataload-myposts-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT],
            [self::class, self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => POP_POSTSCREATION_ROUTE_MYPOSTS,
            self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT => POP_POSTSCREATION_ROUTE_MYPOSTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT => [PoP_Module_Processor_Tables::class, PoP_Module_Processor_Tables::COMPONENT_TABLE_MYPOSTS],
            self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_POSTS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_POSTS_FULLVIEW],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_MYPOSTS];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {

        // Add the format attr
        $tables = array(
            [self::class, self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT],
        );
        $simpleviews = array(
            [self::class, self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
        if (in_array($component, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                if ($categories = gdDataloadAllcontentCategories()) {
                    $ret['category-in'] = $categories;
                }
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('posts', 'poptheme-wassup'));
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('access your posts', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($component, $props);
    }
}



