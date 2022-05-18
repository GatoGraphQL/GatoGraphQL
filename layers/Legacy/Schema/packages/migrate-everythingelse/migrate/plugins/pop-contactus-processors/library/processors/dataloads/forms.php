<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\ContactUsMutations\MutationResolverBridges\ContactUsComponentMutationResolverBridge;

class PoP_ContactUs_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    public final const COMPONENT_DATALOAD_CONTACTUS = 'dataload-contactus';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_CONTACTUS],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_CONTACTUS => POP_CONTACTUS_ROUTE_CONTACTUS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_CONTACTUS:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function validateCaptcha(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_CONTACTUS:
                return true;
        }

        return parent::validateCaptcha($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::COMPONENT_DATALOAD_CONTACTUS => ContactUsComponentMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$component[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_CONTACTUS:
                return [PoP_ContactUs_Module_Processor_FeedbackMessages::class, PoP_ContactUs_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_CONTACTUS];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_CONTACTUS:
                $ret[] = [PoP_ContactUs_Module_Processor_GFForms::class, PoP_ContactUs_Module_Processor_GFForms::COMPONENT_FORM_CONTACTUS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_CONTACTUS:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}


