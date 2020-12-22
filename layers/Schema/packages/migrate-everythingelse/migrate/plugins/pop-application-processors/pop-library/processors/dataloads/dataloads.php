<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_MultidomainProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_INITIALIZEDOMAIN = 'dataload-initializedomain';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_INITIALIZEDOMAIN],
        );
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
                return [PoP_Application_Module_Processor_UserCheckpointMessages::class, PoP_Application_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_DOMAIN];
        }

        return parent::getCheckpointmessageModule($module);
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_INITIALIZEDOMAIN => POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }
    
    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
                $ret = array_merge(
                    $ret,
                    PoP_ApplicationProcessors_Utils::getInitializedomainModules()
                );

                break;
        }

        return $ret;
    }

    public function getBackgroundurls(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDs): array
    {
        $ret = parent::getBackgroundurls($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDs);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
                // Allow PoP SPA to set the backgroundload URLs
                $domain = PoP_Application_Utils::getRequestDomain();
                if ($backgroundurls = HooksAPIFacade::getInstance()->applyFilters('PoP_MultidomainProcessors_Module_Processor_Dataloads:backgroundurls', array(), $domain)) {
                    $ret = array_merge(
                        $ret,
                        $backgroundurls
                    );
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_INITIALIZEDOMAIN:
                // Make it invisible, nothing to show
                $this->appendProp($module, $props, 'class', 'hidden');
                break;
        }
        
        parent::initModelProps($module, $props);
    }
}


