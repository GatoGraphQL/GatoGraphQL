<?php
use PoP\ComponentModel\Facades\ModulePath\ModulePathManagerFacade;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

class AAL_PoPProcessors_Module_Processor_Multiples extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTIPLE_LATESTNOTIFICATIONS = 'multiple-notifications-latestnotifications';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_LATESTNOTIFICATIONS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $inners = array(
            self::MODULE_MULTIPLE_LATESTNOTIFICATIONS => [AAL_PoPProcessors_Module_Processor_Dataloads::class, AAL_PoPProcessors_Module_Processor_Dataloads::MODULE_DATALOAD_LATESTNOTIFICATIONS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
         // Display the dataset also when the block triggers event 'rendered', meaning
         // to do if after the user has logged in with the hover login block
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                $ret['displayBlockDatasetCount']['display-datasetcount-when'] = array(
                    'oncreated',
                    'onrendered',
                );
                $ret['displayBlockDatasetCount']['datasetcount-updatetitle'] = true;
                break;
        }

        return $ret;
    }



    public function getDataFeedbackInterreferencedModulepath(array $module, array &$props): ?array
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                $module_path_manager = ModulePathManagerFacade::getInstance();
                $module_propagation_current_path = $module_path_manager->getPropagationCurrentPath();
                $module_propagation_current_path[] = $module;
                $module_propagation_current_path[] = [AAL_PoPProcessors_Module_Processor_Dataloads::class, AAL_PoPProcessors_Module_Processor_Dataloads::MODULE_DATALOAD_LATESTNOTIFICATIONS];
                return $module_propagation_current_path;
        }

        return parent::getDataFeedbackInterreferencedModulepath($module, $props);
    }

    public function getJsdataFeedback(array $module, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getJsdataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                // Only add if the count is > 0
                if ($dbobjectids) {
                    if ($count = count($dbobjectids)) {
                        $ret['displayBlockDatasetCount']['count'] = $count;
                    }
                }
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                $this->addJsmethod($ret, 'displayBlockDatasetCount');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                $this->appendProp($module, $props, 'class', 'hidden');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-datasetcount-target' => '#'.AAL_PoPProcessors_NotificationUtils::getNotificationcountId(),//[self::class, self::MODULE_ID_NOTIFICATIONSCOUNT],
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
}



