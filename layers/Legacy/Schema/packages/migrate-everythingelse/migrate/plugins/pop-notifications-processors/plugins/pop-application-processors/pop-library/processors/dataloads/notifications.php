<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;

class AAL_PoPProcessors_Module_Processor_NotificationDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS = 'dataload-notifications-scroll-details';
    public final const MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST = 'dataload-notifications-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($componentVariation);

        $inner_modules = array(
            self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS => [GD_AAL_Module_Processor_CustomScrolls::class, GD_AAL_Module_Processor_CustomScrolls::MODULE_SCROLL_NOTIFICATIONS_DETAILS],
            self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST => [GD_AAL_Module_Processor_CustomScrolls::class, GD_AAL_Module_Processor_CustomScrolls::MODULE_SCROLL_NOTIFICATIONS_LIST],
        );

        if ($inner = $inner_modules[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                // Notifications: Need to delete block param 'hist_time' so it can refetch notifications from new users
                // When the block requires user state, make it reload itself when the user logs in/out
                // Important: execute these 2 functions in this order! 1st: delete params, 2nd: do the refetch
                $this->addJsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedInOut');
                break;
        }

        return $ret;
    }

    public function getFormat(array $componentVariation): ?string
    {
        $details = array(
            [self::class, self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST],
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
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
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

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                return [PoP_Module_Processor_DomainFeedbackMessages::class, PoP_Module_Processor_DomainFeedbackMessages::MODULE_FEEDBACKMESSAGE_ITEMLIST];
        }

        return parent::getFeedbackmessageModule($componentVariation);
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_NotificationList::class);
        }

        return parent::getQueryInputOutputHandler($componentVariation);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('notifications', 'pop-notifications-processors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



