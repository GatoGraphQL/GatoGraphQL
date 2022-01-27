<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterSubscriptionMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterUnsubscriptionMutationResolverBridge;

class PoP_Newsletter_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    public const MODULE_DATALOAD_NEWSLETTER = 'dataload-newsletter';
    public const MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION = 'dataload-newsletterunsubscription';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_NEWSLETTER],
            [self::class, self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_NEWSLETTER => POP_NEWSLETTER_ROUTE_NEWSLETTER,
            self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION => POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_NEWSLETTER => NewsletterSubscriptionMutationResolverBridge::class,
            self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION => NewsletterUnsubscriptionMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$module[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
                return [PoP_Newsletter_Module_Processor_FeedbackMessages::class, PoP_Newsletter_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_NEWSLETTER];

            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                return [PoP_Newsletter_Module_Processor_FeedbackMessages::class, PoP_Newsletter_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
                $ret[] = [PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTER];
                break;

            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                $ret[] = [PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTERUNSUBSCRIPTION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



