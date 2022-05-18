<?php
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSitesWassup\FlagMutations\MutationResolverBridges\FlagCustomPostMutationResolverBridge;

class PoP_ContentCreation_Module_Processor_Dataloads extends PoP_Module_Processor_FormDataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOAD_FLAG = 'dataload-flag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_FLAG],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_FLAG => POP_CONTENTCREATION_ROUTE_FLAG,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function validateCaptcha(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                return true;
        }

        return parent::validateCaptcha($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $actionexecuters = array(
            self::COMPONENT_DATALOAD_FLAG => FlagCustomPostMutationResolverBridge::class,
        );
        if ($actionexecuter = $actionexecuters[$component[1]] ?? null) {
            return $actionexecuter;
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                return [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_FLAG];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                $ret[] = [PoP_ContentCreation_Module_Processor_GFForms::class, PoP_ContentCreation_Module_Processor_GFForms::COMPONENT_FORM_FLAG];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms'));
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                return $this->getObjectIDFromURLParam($component, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $component, array &$props, array &$data_properties): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                return \PoPCMSSchema\Posts\Constants\InputNames::POST_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_FLAG:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();;
        }

        return parent::getRelationalTypeResolver($component);
    }
}



