<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_AE_Module_Processor_SectionBlocks extends PoP_CommonAutomatedEmails_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS = 'block-automatedemails-latestcontent-scroll-details';
    public const MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW = 'block-automatedemails-latestcontent-scroll-simpleview';
    public const MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW = 'block-automatedemails-latestcontent-scroll-fullview';
    public const MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL = 'block-automatedemails-latestcontent-scroll-thumbnail';
    public const MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST = 'block-automatedemails-latestcontent-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST => [PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getTitle(array $module, array &$props)
    {
        $cmsService = CMSServiceFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST:
                // Important: this text can only be in the title, and not in the description, because the description is saved in pop-cache/,
                // while the title is set on runtime, so only then we can have the date on the title!
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('Latest content — %s <small><a href="%s">View online</a></small>', 'pop-commonautomatedemails-processors'),
                    date($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat'))),
                    RouteUtils::getRouteURL(POP_BLOG_ROUTE_CONTENT)
                );
        }

        return parent::getTitle($module, $props);
    }

    protected function getDescriptionAbovetitle(array $module, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST:
                return sprintf(
                    '<p>%s</p>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('This is the latest content uploaded to %s during last week. Do you want to add your content? <a href="%s">Click here</a> to share it with our community.', 'pop-commonautomatedemails-processors'),
                        $cmsapplicationapi->getSiteName(),
                        RouteUtils::getRouteURL(POP_CONTENTCREATION_ROUTE_ADDCONTENT)
                    )
                );
        }

        return parent::getDescriptionAbovetitle($module, $props);
    }

    protected function getDescriptionBottom(array $module, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_LATESTCONTENT_SCROLL_LIST:
                return sprintf(
                    '<p>&nbsp;</p><p>%s</p>',
                    sprintf(
                        '<a href="%s">%s</a>',
                        RouteUtils::getRouteURL(POP_BLOG_ROUTE_CONTENT),
                        sprintf(
                            TranslationAPIFacade::getInstance()->__('View all content on %s', 'pop-commonautomatedemails-processors'),
                            $cmsapplicationapi->getSiteName()
                        )
                    )
                );
        }

        return parent::getDescriptionBottom($module, $props);
    }
}


