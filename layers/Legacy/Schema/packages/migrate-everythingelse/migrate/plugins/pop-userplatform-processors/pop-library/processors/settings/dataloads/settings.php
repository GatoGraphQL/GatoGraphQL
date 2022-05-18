<?php
use PoP\Application\QueryInputOutputHandlers\RedirectQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\SettingsMutationResolverBridge;

class PoP_Module_Processor_CustomSettingsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_SETTINGS = 'dataload-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_SETTINGS],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_SETTINGS => POP_USERPLATFORM_ROUTE_SETTINGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return $this->instanceManager->getInstance(SettingsMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return $this->instanceManager->getInstance(RedirectQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                $ret[] = [PoP_Module_Processor_SettingsForms::class, PoP_Module_Processor_SettingsForms::COMPONENT_FORM_SETTINGS];
                break;
        }

        return $ret;
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return [PoP_Module_Processor_SettingsFeedbackMessages::class, PoP_Module_Processor_SettingsFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_SETTINGS];
        }

        return parent::getFeedbackmessageModule($component);
    }
}



