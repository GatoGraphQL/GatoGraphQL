<?php

class PoP_CommonPagesProcessors_Application_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomControlGroups:layouts',
            $this->getSubComponentVariations(...),
            0,
            2
        );
    }

    public function getSubComponentVariations($subComponentVariations, array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEPOST:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATERESETPOST:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EDITPOST:
                $subComponentVariations[] = [GD_CommonPages_Module_Processor_CustomControlButtonGroups::class, GD_CommonPages_Module_Processor_CustomControlButtonGroups::MODULE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ];
                break;
        
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_ACCOUNT:
            case PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEACCOUNT:
                $subComponentVariations[] = [GD_CommonPages_Module_Processor_CustomControlButtonGroups::class, GD_CommonPages_Module_Processor_CustomControlButtonGroups::MODULE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ];
                break;
        }
        
        return $subComponentVariations;
    }
}

/**
 * Initialization
 */
new PoP_CommonPagesProcessors_Application_Hooks();
