<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;
use PoPSitesWassup\LocationMutations\MutationResolverBridges\CreateLocationMutationResolverBridge;

class GD_EM_Module_Processor_CreateLocationDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_CREATELOCATION = 'dataload-createlocation';
    public final const MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION = 'dataload-triggertypeaheadselect-location';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CREATELOCATION],
            [self::class, self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_CREATELOCATION => POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($componentVariation, $props);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return $this->instanceManager->getInstance(CreateLocationMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }

    public function prepareDataPropertiesAfterMutationExecution(array $componentVariation, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($componentVariation, $props, $data_properties);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                if ($target_id = App::getMutationResolutionStore()->getResult($this->instanceManager->getInstance(CreateLocationMutationResolverBridge::class))) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($target_id);
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                return [PoP_Module_Processor_CreateLocationFeedbackMessages::class, PoP_Module_Processor_CreateLocationFeedbackMessages::MODULE_FEEDBACKMESSAGE_CREATELOCATION];
        }

        return parent::getFeedbackmessageModule($componentVariation);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CREATELOCATION:
                $ret[] = [GD_EM_Module_Processor_CreateLocationFrames::class, GD_EM_Module_Processor_CreateLocationFrames::MODULE_FRAME_CREATELOCATIONMAP];
                break;

            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                $ret[] = [PoP_Module_Processor_LocationContents::class, PoP_Module_Processor_LocationContents::MODULE_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }

    protected function getStatusSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                return null;
        }

        return parent::getStatusSubmodule($componentVariation);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                $this->appendProp($componentVariation, $props, 'class', 'hidden');
                break;

            case self::MODULE_DATALOAD_CREATELOCATION:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Adding Location...', 'em-popprocessors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



