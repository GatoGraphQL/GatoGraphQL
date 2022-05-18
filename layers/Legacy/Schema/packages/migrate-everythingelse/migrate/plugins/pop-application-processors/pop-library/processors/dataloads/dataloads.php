<?php

use PoP\Root\Feedback\FeedbackItemResolution;

class PoP_MultidomainProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_INITIALIZEDOMAIN = 'dataload-initializedomain';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_INITIALIZEDOMAIN],
        );
    }

    protected function getCheckpointmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
                return [PoP_Application_Module_Processor_UserCheckpointMessages::class, PoP_Application_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_DOMAIN];
        }

        return parent::getCheckpointmessageModule($componentVariation);
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_INITIALIZEDOMAIN => POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
                $ret = array_merge(
                    $ret,
                    PoP_ApplicationProcessors_Utils::getInitializedomainComponentVariations()
                );

                break;
        }

        return $ret;
    }

    public function getBackgroundurls(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        $ret = parent::getBackgroundurls($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
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

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
                // Make it invisible, nothing to show
                $this->appendProp($componentVariation, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


