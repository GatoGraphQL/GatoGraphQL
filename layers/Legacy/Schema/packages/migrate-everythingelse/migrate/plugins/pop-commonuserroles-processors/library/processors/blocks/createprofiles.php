<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class GD_URE_Module_Processor_CreateProfileBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE = 'block-profileorganization-create';
    public final const COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE = 'block-profileindividual-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE],
            [self::class, self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL,
            self::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileDataloads::class, GD_URE_Module_Processor_CreateProfileDataloads::COMPONENT_DATALOAD_PROFILEORGANIZATION_CREATE];
                $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::COMPONENT_USERACCOUNT_USERLOGGEDINPROMPT];
                break;

            case self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileDataloads::class, GD_URE_Module_Processor_CreateProfileDataloads::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_CREATE];
                $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::COMPONENT_USERACCOUNT_USERLOGGEDINPROMPT];
                break;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_CREATEACCOUNT];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getSubmenuSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE:
                return [PoP_Module_Processor_SubMenus::class, PoP_Module_Processor_SubMenus::COMPONENT_SUBMENU_ACCOUNT];
        }

        return parent::getSubmenuSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE:
                $this->setProp(
                    array(
                        [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_CREATEACCOUNT],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS],
                        [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS],
                    ),
                    $props,
                    'target',
                    '#'.$this->getFrontendId($component, $props).' .collapse'
                );
                $this->appendProp([PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::COMPONENT_USERACCOUNT_USERLOGGEDINPROMPT], $props, 'class', 'well');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



