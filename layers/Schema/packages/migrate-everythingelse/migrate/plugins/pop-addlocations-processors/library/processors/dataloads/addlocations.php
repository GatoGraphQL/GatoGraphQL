<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Locations\TypeResolvers\LocationTypeResolver;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\MutationResolution\MutationResolutionManagerFacade;
use PoPSitesWassup\LocationMutations\MutationResolverBridges\CreateLocationMutationResolverBridge;

class GD_EM_Module_Processor_CreateLocationDataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_CREATELOCATION = 'dataload-createlocation';
    public const MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION = 'dataload-triggertypeaheadselect-location';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CREATELOCATION],
            [self::class, self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_CREATELOCATION => POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return CreateLocationMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    public function prepareDataPropertiesAfterActionexecution(array $module, array &$props, &$data_properties)
    {
        parent::prepareDataPropertiesAfterActionexecution($module, $props, $data_properties);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                $gd_dataload_actionexecution_manager = MutationResolutionManagerFacade::getInstance();
                if ($target_id = $gd_dataload_actionexecution_manager->getResult(CreateLocationMutationResolverBridge::class)) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($target_id);
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return [PoP_Module_Processor_CreateLocationFeedbackMessages::class, PoP_Module_Processor_CreateLocationFeedbackMessages::MODULE_FEEDBACKMESSAGE_CREATELOCATION];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                $ret[] = [GD_EM_Module_Processor_CreateLocationFrames::class, GD_EM_Module_Processor_CreateLocationFrames::MODULE_FRAME_CREATELOCATIONMAP];
                break;

            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                $ret[] = [PoP_Module_Processor_LocationContents::class, PoP_Module_Processor_LocationContents::MODULE_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }

    protected function getStatusSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                return null;
        }

        return parent::getStatusSubmodule($module);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                return LocationTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                $this->appendProp($module, $props, 'class', 'hidden');
                break;

            case self::MODULE_DATALOAD_CREATELOCATION:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Adding Location...', 'em-popprocessors'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



