<?php
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;
use PoPSitesWassup\VolunteerMutations\MutationResolverBridges\VolunteerMutationResolverBridge;

class PoP_Volunteering_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOAD_VOLUNTEER = 'dataload-volunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_VOLUNTEER],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_VOLUNTEER => POP_VOLUNTEERING_ROUTE_VOLUNTEER,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
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

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_VOLUNTEER => VolunteerMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$module[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($module);
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

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return $this->getObjectIDFromURLParam($module, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $module, array &$props, array &$data_properties): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return \PoPCMSSchema\Posts\Constants\InputNames::POST_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_VOLUNTEER:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }
}



