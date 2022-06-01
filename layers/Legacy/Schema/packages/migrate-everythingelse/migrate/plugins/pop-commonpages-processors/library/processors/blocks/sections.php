<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS = 'block-whoweare-scroll-details';
    public final const COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL = 'block-whoweare-scroll-thumbnail';
    public final const COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST = 'block-whoweare-scroll-list';
    public final const COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW = 'block-whoweare-scroll-fullview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_LIST],
            self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLL_FULLVIEW],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_LIST:
            case self::COMPONENT_BLOCK_WHOWEARE_SCROLL_FULLVIEW:
                return TranslationAPIFacade::getInstance()->__('Who we are', 'poptheme-wassup');
        }

        return parent::getTitle($component, $props);
    }
}



