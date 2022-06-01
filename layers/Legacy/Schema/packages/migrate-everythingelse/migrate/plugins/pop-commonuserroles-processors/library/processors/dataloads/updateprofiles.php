<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateOrganizationProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateWithCommunityIndividualProfileMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge;

class GD_URE_Module_Processor_UpdateProfileDataloads extends PoP_Module_Processor_UpdateProfileDataloadsBase
{
    public final const COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE = 'dataload-profileorganization-update';
    public final const COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE = 'dataload-profileindividual-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE],
            [self::class, self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL,
            self::COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE:
                if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
                    return $this->instanceManager->getInstance(CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge::class);
                }
                return $this->instanceManager->getInstance(CreateUpdateOrganizationProfileMutationResolverBridge::class);

            case self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
                if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
                    return $this->instanceManager->getInstance(CreateUpdateWithCommunityIndividualProfileMutationResolverBridge::class);
                }
                return $this->instanceManager->getInstance(CreateUpdateIndividualProfileMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileForms::class, GD_URE_Module_Processor_UpdateProfileForms::COMPONENT_FORM_PROFILEORGANIZATION_UPDATE];
                break;

            case self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileForms::class, GD_URE_Module_Processor_UpdateProfileForms::COMPONENT_FORM_PROFILEINDIVIDUAL_UPDATE];
                break;
        }

        return $ret;
    }

    protected function getCheckpointMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE:
                return [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_PROFILEORGANIZATION];

            case self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
                return [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL];
        }

        return parent::getCheckpointMessageComponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE:
                $this->setProp([PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION], $props, 'action', TranslationAPIFacade::getInstance()->__('edit your user account', 'poptheme-wassup'));
                break;

            case self::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE:
                $this->setProp([PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL], $props, 'action', TranslationAPIFacade::getInstance()->__('edit your user account', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($component, $props);
    }
}



