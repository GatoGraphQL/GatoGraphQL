<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\Engine\ModuleProcessors\DBObjectIDFromURLParamModuleProcessorTrait;
use PoPSitesWassup\ContactUserMutations\MutationResolverBridges\ContactUserMutationResolverBridge;

class PoP_SocialNetwork_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    use DBObjectIDFromURLParamModuleProcessorTrait;

    public const MODULE_DATALOAD_CONTACTUSER = 'dataload-contactuser';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CONTACTUSER],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_CONTACTUSER => POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function validateCaptcha(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return true;
        }

        return parent::validateCaptcha($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_CONTACTUSER => ContactUserMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$module[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return [PoP_SocialNetwork_Module_Processor_FeedbackMessages::class, PoP_SocialNetwork_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_CONTACTUSER];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                $ret[] = [PoP_SocialNetwork_Module_Processor_GFForms::class, PoP_SocialNetwork_Module_Processor_GFForms::MODULE_FORM_CONTACTUSER];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return $this->getDBObjectIDFromURLParam($module, $props, $data_properties);
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    protected function getDBObjectIDParamName(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return POP_INPUTNAME_USERID;
        }
        return null;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }
}



