<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;

class AAL_PoPProcessors_Module_Processor_NotificationDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS = 'dataload-notifications-scroll-details';
    public final const COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST = 'dataload-notifications-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubcomponents($component);

        $inner_components = array(
            self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS => [GD_AAL_Module_Processor_CustomScrolls::class, GD_AAL_Module_Processor_CustomScrolls::COMPONENT_SCROLL_NOTIFICATIONS_DETAILS],
            self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST => [GD_AAL_Module_Processor_CustomScrolls::class, GD_AAL_Module_Processor_CustomScrolls::COMPONENT_SCROLL_NOTIFICATIONS_LIST],
        );

        if ($inner = $inner_components[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                // Notifications: Need to delete block param 'hist_time' so it can refetch notifications from new users
                // When the block requires user state, make it reload itself when the user logs in/out
                // Important: execute these 2 functions in this order! 1st: delete params, 2nd: do the refetch
                $this->addJsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedInOut');
                break;
        }

        return $ret;
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST],
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        $cmsService = CMSServiceFacade::getInstance();
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                // Limit: 2 times the default for posts
                $notifications_query_args = array(
                    'limit' => $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')) * 2,
                );

                $ret = array_merge(
                    $ret,
                    $notifications_query_args
                );
                break;
        }

        return $ret;
    }

    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                return [PoP_Module_Processor_DomainFeedbackMessages::class, PoP_Module_Processor_DomainFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_ITEMLIST];
        }

        return parent::getFeedbackMessageComponent($component);
    }

    public function getQueryInputOutputHandler(\PoP\ComponentModel\Component\Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_NotificationList::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('notifications', 'pop-notifications-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



