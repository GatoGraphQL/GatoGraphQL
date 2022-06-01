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

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_SETTINGS => POP_USERPLATFORM_ROUTE_SETTINGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return $this->instanceManager->getInstance(SettingsMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function getQueryInputOutputHandler(\PoP\ComponentModel\Component\Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return $this->instanceManager->getInstance(RedirectQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                $ret[] = [PoP_Module_Processor_SettingsForms::class, PoP_Module_Processor_SettingsForms::COMPONENT_FORM_SETTINGS];
                break;
        }

        return $ret;
    }

    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_SETTINGS:
                return [PoP_Module_Processor_SettingsFeedbackMessages::class, PoP_Module_Processor_SettingsFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_SETTINGS];
        }

        return parent::getFeedbackMessageComponent($component);
    }
}



