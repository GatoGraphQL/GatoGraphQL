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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($component);

        $inner_components = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => [GD_AAL_Module_Processor_AutomatedEmailsScrolls::class, GD_AAL_Module_Processor_AutomatedEmailsScrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => [GD_AAL_Module_Processor_AutomatedEmailsScrolls::class, GD_AAL_Module_Processor_AutomatedEmailsScrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST],
        );

        if ($inner = $inner_components[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getFormat(array $component): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST],
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        $cmsService = CMSServiceFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
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

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                // Return the notifications from within the last 24 hs
                $yesterday = strtotime("-1 day", ComponentModelModuleInfo::get('time'));
                $ret['hist_time'] = $yesterday;
                break;
        }

        return $ret;
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_NotificationList::class);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('notifications', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}


