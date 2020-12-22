<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_CommonPagesProcessors_Application_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomControlGroups:layouts',
            array($this, 'getSubmodules'),
            0,
            2
        );
    }

    public function getSubmodules($submodules, array $module)
    {
        switch ($module[1]) {
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEPOST:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATERESETPOST:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EDITPOST:
                $submodules[] = [GD_CommonPages_Module_Processor_CustomControlButtonGroups::class, GD_CommonPages_Module_Processor_CustomControlButtonGroups::MODULE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ];
                break;
        
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_ACCOUNT:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEACCOUNT:
                $submodules[] = [GD_CommonPages_Module_Processor_CustomControlButtonGroups::class, GD_CommonPages_Module_Processor_CustomControlButtonGroups::MODULE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ];
                break;
        }
        
        return $submodules;
    }
}

/**
 * Initialization
 */
new PoP_CommonPagesProcessors_Application_Hooks();
