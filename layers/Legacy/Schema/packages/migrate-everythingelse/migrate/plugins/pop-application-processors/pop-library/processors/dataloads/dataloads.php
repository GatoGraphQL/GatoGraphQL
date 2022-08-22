<?php

use PoP\Root\Feedback\FeedbackItemResolution;

class PoP_MultidomainProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_INITIALIZEDOMAIN = 'dataload-initializedomain';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_INITIALIZEDOMAIN,
        );
    }

    protected function getCheckpointMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_INITIALIZEDOMAIN:
                return [PoP_Application_Module_Processor_UserCheckpointMessages::class, PoP_Application_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_DOMAIN];
        }

        return parent::getCheckpointMessageComponent($component);
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_INITIALIZEDOMAIN => POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_INITIALIZEDOMAIN:
                $ret = array_merge(
                    $ret,
                    PoP_ApplicationProcessors_Utils::getInitializedomainComponents()
                );

                break;
        }

        return $ret;
    }

    public function getBackgroundurls(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        $ret = parent::getBackgroundurls($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_INITIALIZEDOMAIN:
                // Allow PoP SPA to set the backgroundload URLs
                $domain = PoP_Application_Utils::getRequestDomain();
                if ($backgroundurls = \PoP\Root\App::applyFilters('PoP_MultidomainProcessors_Module_Processor_Dataloads:backgroundurls', array(), $domain)) {
                    $ret = array_merge(
                        $ret,
                        $backgroundurls
                    );
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_INITIALIZEDOMAIN:
                // Make it invisible, nothing to show
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


