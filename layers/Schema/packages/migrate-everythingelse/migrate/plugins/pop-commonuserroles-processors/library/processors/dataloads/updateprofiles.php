<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateOrganizationProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateWithCommunityIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge;

class GD_URE_Module_Processor_UpdateProfileDataloads extends PoP_Module_Processor_UpdateProfileDataloadsBase
{
    public const MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE = 'dataload-profileorganization-update';
    public const MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE = 'dataload-profileindividual-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE],
            [self::class, self::MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL,
            self::MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE:
                if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
                    return CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge::class;
                }
                return CreateUpdateOrganizationProfileMutationResolverBridge::class;

            case self::MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
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
            case self::MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileForms::class, GD_URE_Module_Processor_UpdateProfileForms::MODULE_FORM_PROFILEORGANIZATION_UPDATE];
                break;

            case self::MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileForms::class, GD_URE_Module_Processor_UpdateProfileForms::MODULE_FORM_PROFILEINDIVIDUAL_UPDATE];
                break;
        }

        return $ret;
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE:
                return [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_PROFILEORGANIZATION];

            case self::MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
                return [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL];
        }

        return parent::getCheckpointmessageModule($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE:
                $this->setProp([PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION], $props, 'action', TranslationAPIFacade::getInstance()->__('edit your user account', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
                $this->setProp([PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL], $props, 'action', TranslationAPIFacade::getInstance()->__('edit your user account', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($module, $props);
    }
}



