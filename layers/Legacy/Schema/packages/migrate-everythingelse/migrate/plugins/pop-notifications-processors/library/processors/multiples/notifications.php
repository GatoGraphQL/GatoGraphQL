<?php
use PoP\ComponentModel\Facades\ModulePath\ModulePathManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

class AAL_PoPProcessors_Module_Processor_Multiples extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_LATESTNOTIFICATIONS = 'multiple-notifications-latestnotifications';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_LATESTNOTIFICATIONS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $inners = array(
            self::MODULE_MULTIPLE_LATESTNOTIFICATIONS => [AAL_PoPProcessors_Module_Processor_Dataloads::class, AAL_PoPProcessors_Module_Processor_Dataloads::MODULE_DATALOAD_LATESTNOTIFICATIONS],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
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



    public function getDataFeedbackInterreferencedComponentVariationPath(array $componentVariation, array &$props): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                $module_path_manager = ModulePathManagerFacade::getInstance();
                $module_propagation_current_path = $module_path_manager->getPropagationCurrentPath();
                $module_propagation_current_path[] = $componentVariation;
                $module_propagation_current_path[] = [AAL_PoPProcessors_Module_Processor_Dataloads::class, AAL_PoPProcessors_Module_Processor_Dataloads::MODULE_DATALOAD_LATESTNOTIFICATIONS];
                return $module_propagation_current_path;
        }

        return parent::getDataFeedbackInterreferencedComponentVariationPath($componentVariation, $props);
    }

    public function getJsdataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getJsdataFeedback($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        switch ($componentVariation[1]) {
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

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                $this->addJsmethod($ret, 'displayBlockDatasetCount');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_LATESTNOTIFICATIONS:
                $this->appendProp($componentVariation, $props, 'class', 'hidden');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-datasetcount-target' => '#'.AAL_PoPProcessors_NotificationUtils::getNotificationcountId(),//[self::class, self::MODULE_ID_NOTIFICATIONSCOUNT],
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



