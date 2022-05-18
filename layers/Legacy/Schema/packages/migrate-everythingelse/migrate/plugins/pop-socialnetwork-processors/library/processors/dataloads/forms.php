<?php
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\ContactUserMutations\MutationResolverBridges\ContactUserMutationResolverBridge;

class PoP_SocialNetwork_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOAD_CONTACTUSER = 'dataload-contactuser';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CONTACTUSER],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_CONTACTUSER => POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
            default => parent::getRelevantRoute($module, $props),
        };
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

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_CONTACTUSER => ContactUserMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$module[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($module);
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

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return $this->getObjectIDFromURLParam($module, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $module, array &$props, array &$data_properties): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return \PoPCMSSchema\Users\Constants\InputNames::USER_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }
}



