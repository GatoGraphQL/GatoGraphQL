<?php

use PoP\Root\Feedback\FeedbackItemResolution;

class PoP_MultidomainProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_INITIALIZEDOMAIN = 'dataload-initializedomain';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_INITIALIZEDOMAIN],
        );
    }

    protected function getCheckpointmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_INITIALIZEDOMAIN:
                return [PoP_Application_Module_Processor_UserCheckpointMessages::class, PoP_Application_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_DOMAIN];
        }

        return parent::getCheckpointmessageModule($component);
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_INITIALIZEDOMAIN => POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_INITIALIZEDOMAIN:
                $ret = array_merge(
                    $ret,
                    PoP_ApplicationProcessors_Utils::getInitializedomainComponents()
                );

                break;
        }

        return $ret;
    }

    public function getBackgroundurls(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        $ret = parent::getBackgroundurls($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);

        switch ($component[1]) {
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

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_INITIALIZEDOMAIN:
                // Make it invisible, nothing to show
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


