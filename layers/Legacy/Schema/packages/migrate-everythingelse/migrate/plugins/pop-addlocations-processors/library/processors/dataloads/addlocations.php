<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;
use PoPSitesWassup\LocationMutations\MutationResolverBridges\CreateLocationMutationResolverBridge;

class GD_EM_Module_Processor_CreateLocationDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_CREATELOCATION = 'dataload-createlocation';
    public final const MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION = 'dataload-triggertypeaheadselect-location';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CREATELOCATION],
            [self::class, self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_CREATELOCATION => POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return $this->instanceManager->getInstance(CreateLocationMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }

    public function prepareDataPropertiesAfterMutationExecution(array $module, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($module, $props, $data_properties);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                if ($target_id = App::getMutationResolutionStore()->getResult($this->instanceManager->getInstance(CreateLocationMutationResolverBridge::class))) {
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

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
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



