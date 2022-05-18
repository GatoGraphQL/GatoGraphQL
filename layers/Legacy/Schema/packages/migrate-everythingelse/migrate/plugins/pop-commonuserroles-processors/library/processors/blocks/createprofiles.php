<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class GD_URE_Module_Processor_CreateProfileBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_PROFILEORGANIZATION_CREATE = 'block-profileorganization-create';
    public final const MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE = 'block-profileindividual-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_PROFILEORGANIZATION_CREATE],
            [self::class, self::MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL,
            self::MODULE_BLOCK_PROFILEORGANIZATION_CREATE => POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_PROFILEORGANIZATION_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileDataloads::class, GD_URE_Module_Processor_CreateProfileDataloads::MODULE_DATALOAD_PROFILEORGANIZATION_CREATE];
                $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::MODULE_USERACCOUNT_USERLOGGEDINPROMPT];
                break;

            case self::MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE:
                $ret[] = [GD_URE_Module_Processor_CreateProfileDataloads::class, GD_URE_Module_Processor_CreateProfileDataloads::MODULE_DATALOAD_PROFILEINDIVIDUAL_CREATE];
                $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::MODULE_USERACCOUNT_USERLOGGEDINPROMPT];
                break;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_PROFILEORGANIZATION_CREATE:
            case self::MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEACCOUNT];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getSubmenuSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_PROFILEORGANIZATION_CREATE:
            case self::MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE:
                return [PoP_Module_Processor_SubMenus::class, PoP_Module_Processor_SubMenus::MODULE_SUBMENU_ACCOUNT];
        }

        return parent::getSubmenuSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_BLOCK_PROFILEORGANIZATION_CREATE:
            case self::MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE:
                $this->setProp(
                    array(
                        [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEACCOUNT],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS],
                        [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS],
                    ),
                    $props,
                    'target',
                    '#'.$this->getFrontendId($module, $props).' .collapse'
                );
                $this->appendProp([PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::MODULE_USERACCOUNT_USERLOGGEDINPROMPT], $props, 'class', 'well');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



