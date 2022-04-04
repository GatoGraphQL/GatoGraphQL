<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\ShareMutations\MutationResolverBridges\ShareByEmailMutationResolverBridge;

class PoP_Share_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    public final const MODULE_DATALOAD_SHAREBYEMAIL = 'dataload-sharebyemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SHAREBYEMAIL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_SHAREBYEMAIL => POP_SHARE_ROUTE_SHAREBYEMAIL,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SHAREBYEMAIL:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function validateCaptcha(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SHAREBYEMAIL:
                return true;
        }

        return parent::validateCaptcha($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_SHAREBYEMAIL => ShareByEmailMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$module[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SHAREBYEMAIL:
                return [PoP_Share_Module_Processor_FeedbackMessages::class, PoP_Share_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_SHAREBYEMAIL];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_SHAREBYEMAIL:
                $ret[] = [PoP_Share_Module_Processor_GFForms::class, PoP_Share_Module_Processor_GFForms::MODULE_FORM_SHAREBYEMAIL];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SHAREBYEMAIL:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}


