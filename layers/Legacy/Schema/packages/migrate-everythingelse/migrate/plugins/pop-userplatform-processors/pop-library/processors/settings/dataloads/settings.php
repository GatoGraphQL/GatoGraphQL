<?php
use PoP\Application\QueryInputOutputHandlers\RedirectQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\SettingsMutationResolverBridge;

class PoP_Module_Processor_CustomSettingsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_SETTINGS = 'dataload-settings';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SETTINGS],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_SETTINGS => POP_USERPLATFORM_ROUTE_SETTINGS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SETTINGS:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SETTINGS:
                return $this->instanceManager->getInstance(SettingsMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SETTINGS:
                return $this->instanceManager->getInstance(RedirectQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_SETTINGS:
                $ret[] = [PoP_Module_Processor_SettingsForms::class, PoP_Module_Processor_SettingsForms::MODULE_FORM_SETTINGS];
                break;
        }

        return $ret;
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SETTINGS:
                return [PoP_Module_Processor_SettingsFeedbackMessages::class, PoP_Module_Processor_SettingsFeedbackMessages::MODULE_FEEDBACKMESSAGE_SETTINGS];
        }

        return parent::getFeedbackmessageModule($module);
    }
}



