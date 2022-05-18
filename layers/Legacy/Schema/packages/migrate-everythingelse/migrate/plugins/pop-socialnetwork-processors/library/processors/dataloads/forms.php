<?php
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\ContactUserMutations\MutationResolverBridges\ContactUserMutationResolverBridge;

class PoP_SocialNetwork_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOAD_CONTACTUSER = 'dataload-contactuser';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CONTACTUSER],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_CONTACTUSER => POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($componentVariation, $props);
    }

    protected function validateCaptcha(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return true;
        }

        return parent::validateCaptcha($componentVariation, $props);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::MODULE_DATALOAD_CONTACTUSER => ContactUserMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$componentVariation[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return [PoP_SocialNetwork_Module_Processor_FeedbackMessages::class, PoP_SocialNetwork_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_CONTACTUSER];
        }

        return parent::getFeedbackmessageModule($componentVariation);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                $ret[] = [PoP_SocialNetwork_Module_Processor_GFForms::class, PoP_SocialNetwork_Module_Processor_GFForms::MODULE_FORM_CONTACTUSER];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return $this->getObjectIDFromURLParam($componentVariation, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $componentVariation, array &$props, array &$data_properties): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return \PoPCMSSchema\Users\Constants\InputNames::USER_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTACTUSER:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }
}



