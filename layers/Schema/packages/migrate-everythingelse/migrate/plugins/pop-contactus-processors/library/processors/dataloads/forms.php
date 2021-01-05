<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\ContactUsMutations\MutationResolverBridges\ContactUsComponentMutationResolverBridge;

class PoP_ContactUs_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    public const MODULE_DATALOAD_CONTACTUS = 'dataload-contactus';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CONTACTUS],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_CONTACTUS => POP_CONTACTUS_ROUTE_CONTACTUS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUS:
                return GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function validateCaptcha(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUS:
                return true;
        }

        return parent::validateCaptcha($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_CONTACTUS => ContactUsComponentMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$module[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUS:
                return [PoP_ContactUs_Module_Processor_FeedbackMessages::class, PoP_ContactUs_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_CONTACTUS];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUS:
                $ret[] = [PoP_ContactUs_Module_Processor_GFForms::class, PoP_ContactUs_Module_Processor_GFForms::MODULE_FORM_CONTACTUS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUS:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}


