<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterSubscriptionMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterUnsubscriptionMutationResolverBridge;

class PoP_Newsletter_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    public final const MODULE_DATALOAD_NEWSLETTER = 'dataload-newsletter';
    public final const MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION = 'dataload-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_NEWSLETTER],
            [self::class, self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_NEWSLETTER => POP_NEWSLETTER_ROUTE_NEWSLETTER,
            self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION => POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_NEWSLETTER => NewsletterSubscriptionMutationResolverBridge::class,
            self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION => NewsletterUnsubscriptionMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$component[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
                return [PoP_Newsletter_Module_Processor_FeedbackMessages::class, PoP_Newsletter_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_NEWSLETTER];

            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                return [PoP_Newsletter_Module_Processor_FeedbackMessages::class, PoP_Newsletter_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
                $ret[] = [PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTER];
                break;

            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                $ret[] = [PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTERUNSUBSCRIPTION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_NEWSLETTER:
            case self::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



