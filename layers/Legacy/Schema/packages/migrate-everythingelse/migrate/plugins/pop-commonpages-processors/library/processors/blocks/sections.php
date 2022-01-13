<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_WHOWEARE_SCROLL_DETAILS = 'block-whoweare-scroll-details';
    public const MODULE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL = 'block-whoweare-scroll-thumbnail';
    public const MODULE_BLOCK_WHOWEARE_SCROLL_LIST = 'block-whoweare-scroll-list';
    public const MODULE_BLOCK_WHOWEARE_SCROLL_FULLVIEW = 'block-whoweare-scroll-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_WHOWEARE_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_WHOWEARE_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_WHOWEARE_SCROLL_FULLVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_WHOWEARE_SCROLL_DETAILS => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_BLOCK_WHOWEARE_SCROLL_LIST => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            self::MODULE_BLOCK_WHOWEARE_SCROLL_FULLVIEW => POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_WHOWEARE_SCROLL_DETAILS => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_WHOWEARE_SCROLL_DETAILS],
            self::MODULE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_WHOWEARE_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_WHOWEARE_SCROLL_LIST => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_WHOWEARE_SCROLL_LIST],
            self::MODULE_BLOCK_WHOWEARE_SCROLL_FULLVIEW => [GD_Custom_Module_Processor_CustomSectionDataloads::class, GD_Custom_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_WHOWEARE_SCROLL_FULLVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_WHOWEARE_SCROLL_DETAILS:
            case self::MODULE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_WHOWEARE_SCROLL_LIST:
            case self::MODULE_BLOCK_WHOWEARE_SCROLL_FULLVIEW:
                return TranslationAPIFacade::getInstance()->__('Who we are', 'poptheme-wassup');
        }

        return parent::getTitle($module, $props);
    }
}



