<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;
use PoPSitesWassup\LocationMutations\MutationResolverBridges\CreateLocationMutationResolverBridge;

class GD_EM_Module_Processor_CreateLocationDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_CREATELOCATION = 'dataload-createlocation';
    public final const COMPONENT_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION = 'dataload-triggertypeaheadselect-location';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_CREATELOCATION,
            self::COMPONENT_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_CREATELOCATION => POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CREATELOCATION:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CREATELOCATION:
                return $this->instanceManager->getInstance(CreateLocationMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function prepareDataPropertiesAfterMutationExecution(\PoP\ComponentModel\Component\Component $component, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($component, $props, $data_properties);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                if ($target_id = App::getMutationResolutionStore()->getResult($this->instanceManager->getInstance(CreateLocationMutationResolverBridge::class))) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($target_id);
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CREATELOCATION:
                return [PoP_Module_Processor_CreateLocationFeedbackMessages::class, PoP_Module_Processor_CreateLocationFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_CREATELOCATION];
        }

        return parent::getFeedbackMessageComponent($component);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CREATELOCATION:
                $ret[] = [GD_EM_Module_Processor_CreateLocationFrames::class, GD_EM_Module_Processor_CreateLocationFrames::COMPONENT_FRAME_CREATELOCATIONMAP];
                break;

            case self::COMPONENT_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                $ret[] = [PoP_Module_Processor_LocationContents::class, PoP_Module_Processor_LocationContents::COMPONENT_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }

    protected function getStatusSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                return null;
        }

        return parent::getStatusSubcomponent($component);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_TRIGGERTYPEAHEADSELECT_LOCATION:
                $this->appendProp($component, $props, 'class', 'hidden');
                break;

            case self::COMPONENT_DATALOAD_CREATELOCATION:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Adding Location...', 'em-popprocessors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



