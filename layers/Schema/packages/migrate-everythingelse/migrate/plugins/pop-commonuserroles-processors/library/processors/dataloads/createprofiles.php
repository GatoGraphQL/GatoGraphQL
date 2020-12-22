<?php

use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\CreateUpdateIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\CreateUpdateOrganizationProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\CreateUpdateWithCommunityIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge;

class GD_URE_Module_Processor_CreateProfileDataloads extends PoP_Module_Processor_CreateProfileDataloadsBase
{
    public const MODULE_DATALOAD_PROFILEORGANIZATION_CREATE = 'dataload-profileorganization-create';
    public const MODULE_DATALOAD_PROFILEINDIVIDUAL_CREATE = 'dataload-profileindividual-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_PROFILEORGANIZATION_CREATE],
            [self::class, self::MODULE_DATALOAD_PROFILEINDIVIDUAL_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_PROFILEINDIVIDUAL_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL,
            self::MODULE_DATALOAD_PROFILEORGANIZATION_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_PROFILEINDIVIDUAL_CREATE:
            case self::MODULE_DATALOAD_PROFILEORGANIZATION_CREATE:
                return GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_PROFILEORGANIZATION_CREATE:
                if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
                    return CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge::class;
                }
                return CreateUpdateOrganizationProfileMutationResolverBridge::class;

            case self::MODULE_DATALOAD_PROFILEINDIVIDUAL_CREATE:
                if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
                    return CreateUpdateWithCommunityIndividualProfileMutationResolverBridge::class;
                }
                return CreateUpdateIndividualProfileMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_PROFILEORGANIZATION_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileForms::class, GD_URE_Module_Processor_CreateProfileForms::MODULE_FORM_PROFILEORGANIZATION_CREATE];
                break;

            case self::MODULE_DATALOAD_PROFILEINDIVIDUAL_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileForms::class, GD_URE_Module_Processor_CreateProfileForms::MODULE_FORM_PROFILEINDIVIDUAL_CREATE];
                break;
        }

        return $ret;
    }
}



