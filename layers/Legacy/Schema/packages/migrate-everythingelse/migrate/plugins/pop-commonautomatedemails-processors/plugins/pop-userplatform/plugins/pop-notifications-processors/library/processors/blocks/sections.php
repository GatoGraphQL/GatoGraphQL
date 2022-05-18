<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_AAL_AE_Module_Processor_SectionBlocks extends PoP_CommonAutomatedEmails_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS = 'block-automatedemails-scroll-details';
    public final const MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST = 'block-automatedemails-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($componentVariation);

        $inner_componentVariations = array(
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => [PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => [PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::class, PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );

        if ($inner = $inner_componentVariations[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        $cmsService = CMSServiceFacade::getInstance();
        switch ($componentVariation[1]) {
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

        return parent::getTitle($componentVariation, $props);
    }

    protected function getDescriptionAbovetitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                return sprintf(
                    '<p>%s</p>',
                    TranslationAPIFacade::getInstance()->__('These are your unread notifications from the last day.', 'pop-commonautomatedemails-processors')
                );
        }

        return parent::getDescriptionAbovetitle($componentVariation, $props);
    }

    protected function getDescriptionBottom(array $componentVariation, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
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

        return parent::getDescriptionBottom($componentVariation, $props);
    }
}


