<?php

class PoP_CommonPagesProcessors_Application_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomControlGroups:layouts',
            $this->getSubComponents(...),
            0,
            2
        );
    }

    public function getSubComponents($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEPOST:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATERESETPOST:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EDITPOST:
                $subComponents[] = [GD_CommonPages_Module_Processor_CustomControlButtonGroups::class, GD_CommonPages_Module_Processor_CustomControlButtonGroups::MODULE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ];
                break;
        
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_ACCOUNT:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEACCOUNT:
                $subComponents[] = [GD_CommonPages_Module_Processor_CustomControlButtonGroups::class, GD_CommonPages_Module_Processor_CustomControlButtonGroups::MODULE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ];
                break;
        }
        
        return $subComponents;
    }
}

/**
 * Initialization
 */
new PoP_CommonPagesProcessors_Application_Hooks();
