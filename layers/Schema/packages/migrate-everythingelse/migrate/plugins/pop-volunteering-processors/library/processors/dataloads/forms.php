<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\Engine\ModuleProcessors\DBObjectIDFromURLParamModuleProcessorTrait;
use PoPSitesWassup\VolunteerMutations\MutationResolverBridges\VolunteerMutationResolverBridge;

class PoP_Volunteering_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    use DBObjectIDFromURLParamModuleProcessorTrait;

    public const MODULE_DATALOAD_VOLUNTEER = 'dataload-volunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_VOLUNTEER],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_VOLUNTEER => POP_VOLUNTEERING_ROUTE_VOLUNTEER,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function validateCaptcha(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return true;
        }

        return parent::validateCaptcha($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_VOLUNTEER => VolunteerMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$module[1]]) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return [PoP_Volunteering_Module_Processor_FeedbackMessages::class, PoP_Volunteering_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_VOLUNTEER];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                $ret[] = [PoP_Volunteering_Module_Processor_GFForms::class, PoP_Volunteering_Module_Processor_GFForms::MODULE_FORM_VOLUNTEER];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return $this->getDBObjectIDFromURLParam($module, $props, $data_properties);
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    protected function getDBObjectIDParamName(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return POP_INPUTNAME_POSTID;
        }
        return null;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return CustomPostTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }
}



