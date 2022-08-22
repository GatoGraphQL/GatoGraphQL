<?php
use PoP\ComponentModel\Facades\ComponentPath\ComponentPathManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

class AAL_PoPProcessors_Module_Processor_Multiples extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_LATESTNOTIFICATIONS = 'multiple-notifications-latestnotifications';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_LATESTNOTIFICATIONS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $inners = array(
            self::COMPONENT_MULTIPLE_LATESTNOTIFICATIONS => [AAL_PoPProcessors_Module_Processor_Dataloads::class, AAL_PoPProcessors_Module_Processor_Dataloads::COMPONENT_DATALOAD_LATESTNOTIFICATIONS],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component->name) {
         // Display the dataset also when the block triggers event 'rendered', meaning
         // to do if after the user has logged in with the hover login block
            case self::COMPONENT_MULTIPLE_LATESTNOTIFICATIONS:
                $ret['displayBlockDatasetCount']['display-datasetcount-when'] = array(
                    'oncreated',
                    'onrendered',
                );
                $ret['displayBlockDatasetCount']['datasetcount-updatetitle'] = true;
                break;
        }

        return $ret;
    }



    public function getDataFeedbackInterreferencedComponentPath(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_LATESTNOTIFICATIONS:
                $component_path_manager = ComponentPathManagerFacade::getInstance();
                $component_propagation_current_path = $component_path_manager->getPropagationCurrentPath();
                $component_propagation_current_path[] = $component;
                $component_propagation_current_path[] = [AAL_PoPProcessors_Module_Processor_Dataloads::class, AAL_PoPProcessors_Module_Processor_Dataloads::COMPONENT_DATALOAD_LATESTNOTIFICATIONS];
                return $component_propagation_current_path;
        }

        return parent::getDataFeedbackInterreferencedComponentPath($component, $props);
    }

    public function getJsdataFeedback(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        $ret = parent::getJsdataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_LATESTNOTIFICATIONS:
                // Only add if the count is > 0
                if ($objectIDs) {
                    if ($count = count($objectIDs)) {
                        $ret['displayBlockDatasetCount']['count'] = $count;
                    }
                }
                break;
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_LATESTNOTIFICATIONS:
                $this->addJsmethod($ret, 'displayBlockDatasetCount');
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_LATESTNOTIFICATIONS:
                $this->appendProp($component, $props, 'class', 'hidden');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-datasetcount-target' => '#'.AAL_PoPProcessors_NotificationUtils::getNotificationcountId(),//self::COMPONENT_ID_NOTIFICATIONSCOUNT,
                    )
                );
                break;
        }

        parent::initModelProps($component, $props);
    }
}



