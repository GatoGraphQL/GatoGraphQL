<?php
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;

class PoPTheme_Wassup_AAL_AE_Module_Processor_SectionDataloads extends PoP_CommonAutomatedEmails_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS = 'dataload-automatedemails-scroll-details';
    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST = 'dataload-automatedemails-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($componentVariation);

        $inner_modules = array(
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => [GD_AAL_Module_Processor_AutomatedEmailsScrolls::class, GD_AAL_Module_Processor_AutomatedEmailsScrolls::MODULE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS],
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => [GD_AAL_Module_Processor_AutomatedEmailsScrolls::class, GD_AAL_Module_Processor_AutomatedEmailsScrolls::MODULE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST],
        );

        if ($inner = $inner_modules[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getFormat(array $componentVariation): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );
        if (in_array($componentVariation, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($componentVariation, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        $cmsService = CMSServiceFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                // Return the notifications from within the last 24 hs (timestamp is runtime, it must not be cached)
                $ret['hist_time_compare'] = '>=';

                // Bring only the non-read notifications
                $ret['joinstatus'] = true;
                $ret['status'] = 'null';

                // Limit: 2 times the default for posts
                $ret['limit'] = $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')) * 2;
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                // Return the notifications from within the last 24 hs
                $yesterday = strtotime("-1 day", ComponentModelModuleInfo::get('time'));
                $ret['hist_time'] = $yesterday;
                break;
        }

        return $ret;
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_NotificationList::class);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('notifications', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


