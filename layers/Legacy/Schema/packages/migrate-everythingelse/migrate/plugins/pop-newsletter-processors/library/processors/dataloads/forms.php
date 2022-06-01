<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterSubscriptionMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterUnsubscriptionMutationResolverBridge;

class PoP_Newsletter_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    public final const COMPONENT_DATALOAD_NEWSLETTER = 'dataload-newsletter';
    public final const COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION = 'dataload-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_NEWSLETTER],
            [self::class, self::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_NEWSLETTER => POP_NEWSLETTER_ROUTE_NEWSLETTER,
            self::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION => POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NEWSLETTER:
            case self::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::COMPONENT_DATALOAD_NEWSLETTER => NewsletterSubscriptionMutationResolverBridge::class,
            self::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION => NewsletterUnsubscriptionMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$component->name] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NEWSLETTER:
                return [PoP_Newsletter_Module_Processor_FeedbackMessages::class, PoP_Newsletter_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_NEWSLETTER];

            case self::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                return [PoP_Newsletter_Module_Processor_FeedbackMessages::class, PoP_Newsletter_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION];
        }

        return parent::getFeedbackMessageComponent($component);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NEWSLETTER:
                $ret[] = [PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::COMPONENT_FORM_NEWSLETTER];
                break;

            case self::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                $ret[] = [PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::COMPONENT_FORM_NEWSLETTERUNSUBSCRIPTION];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_NEWSLETTER:
            case self::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



