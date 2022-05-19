<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\ShareMutations\MutationResolverBridges\ShareByEmailMutationResolverBridge;

class PoP_Share_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    public final const COMPONENT_DATALOAD_SHAREBYEMAIL = 'dataload-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_SHAREBYEMAIL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_SHAREBYEMAIL => POP_SHARE_ROUTE_SHAREBYEMAIL,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SHAREBYEMAIL:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function validateCaptcha(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SHAREBYEMAIL:
                return true;
        }

        return parent::validateCaptcha($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::COMPONENT_DATALOAD_SHAREBYEMAIL => ShareByEmailMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$component[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SHAREBYEMAIL:
                return [PoP_Share_Module_Processor_FeedbackMessages::class, PoP_Share_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_SHAREBYEMAIL];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SHAREBYEMAIL:
                $ret[] = [PoP_Share_Module_Processor_GFForms::class, PoP_Share_Module_Processor_GFForms::COMPONENT_FORM_SHAREBYEMAIL];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SHAREBYEMAIL:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}


