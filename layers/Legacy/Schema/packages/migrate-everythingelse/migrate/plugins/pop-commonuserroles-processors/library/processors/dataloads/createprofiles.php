<?php

use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateOrganizationProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateWithCommunityIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge;

class GD_URE_Module_Processor_CreateProfileDataloads extends PoP_Module_Processor_CreateProfileDataloadsBase
{
    public final const COMPONENT_DATALOAD_PROFILEORGANIZATION_CREATE = 'dataload-profileorganization-create';
    public final const COMPONENT_DATALOAD_PROFILEINDIVIDUAL_CREATE = 'dataload-profileindividual-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_PROFILEORGANIZATION_CREATE],
            [self::class, self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL,
            self::COMPONENT_DATALOAD_PROFILEORGANIZATION_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_CREATE:
            case self::COMPONENT_DATALOAD_PROFILEORGANIZATION_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_PROFILEORGANIZATION_CREATE:
                if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
                    return $this->instanceManager->getInstance(CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge::class);
                }
                return $this->instanceManager->getInstance(CreateUpdateOrganizationProfileMutationResolverBridge::class);

            case self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_CREATE:
                if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
                    return $this->instanceManager->getInstance(CreateUpdateWithCommunityIndividualProfileMutationResolverBridge::class);
                }
                return $this->instanceManager->getInstance(CreateUpdateIndividualProfileMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_PROFILEORGANIZATION_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileForms::class, GD_URE_Module_Processor_CreateProfileForms::COMPONENT_FORM_PROFILEORGANIZATION_CREATE];
                break;

            case self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileForms::class, GD_URE_Module_Processor_CreateProfileForms::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE];
                break;
        }

        return $ret;
    }
}



