<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_WHOWEARE_SCROLL_DETAILS = 'block-whoweare-scroll-details';
    public final const MODULE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL = 'block-whoweare-scroll-thumbnail';
    public final const MODULE_BLOCK_WHOWEARE_SCROLL_LIST = 'block-whoweare-scroll-list';
    public final const MODULE_BLOCK_WHOWEARE_SCROLL_FULLVIEW = 'block-whoweare-scroll-fullview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST],
            [self::class, self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST],
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST:
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW:
                return TranslationAPIFacade::getInstance()->__('Who we are', 'poptheme-wassup');
        }

        return parent::getTitle($component, $props);
    }
}



