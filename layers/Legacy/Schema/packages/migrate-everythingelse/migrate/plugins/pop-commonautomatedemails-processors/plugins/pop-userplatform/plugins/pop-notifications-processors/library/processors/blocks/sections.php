<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_AAL_AE_Module_Processor_SectionBlocks extends PoP_CommonAutomatedEmails_Module_Processor_SectionBlocksBase
{
    public const MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS = 'block-automatedemails-scroll-details';
    public const MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST = 'block-automatedemails-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($module);

        $inner_modules = array(
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => [PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => [PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );

        if ($inner = $inner_modules[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
    {
        $cmsService = CMSServiceFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                // Important: this text can only be in the title, and not in the description, because the description is saved in pop-cache/,
                // while the title is set on runtime, so only then we can have the date on the title!
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('Your daily notifications — %s <small><a href="%s">View online</a></small>', 'pop-commonautomatedemails-processors'),
                    date($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat'))),
                    RouteUtils::getRouteURL(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS)
                );
        }

        return parent::getTitle($module, $props);
    }

    protected function getDescriptionAbovetitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                return sprintf(
                    '<p>%s</p>',
                    TranslationAPIFacade::getInstance()->__('These are your unread notifications from the last day.', 'pop-commonautomatedemails-processors')
                );
        }

        return parent::getDescriptionAbovetitle($module, $props);
    }

    protected function getDescriptionBottom(array $module, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                return sprintf(
                    '<p>&nbsp;</p><p>%s</p>',
                    sprintf(
                        '<a href="%s">%s</a>',
                        RouteUtils::getRouteURL(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS),
                        sprintf(
                            TranslationAPIFacade::getInstance()->__('View all your notifications on %s', 'pop-commonautomatedemails-processors'),
                            $cmsapplicationapi->getSiteName()
                        )
                    )
                );
        }

        return parent::getDescriptionBottom($module, $props);
    }
}


