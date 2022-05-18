<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class PoP_CommonUserRoles_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutComponents(&$layouts, $handle, $format = '')
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $field_organization = $fieldQueryInterpreter->getField(
            'hasRole',
            [
                'role' => GD_URE_ROLE_ORGANIZATION,
            ]
        );
        $field_individual = $fieldQueryInterpreter->getField(
            'hasRole',
            [
                'role' => GD_URE_ROLE_INDIVIDUAL,
            ]
        );
        switch ($handle) {
            case POP_MULTILAYOUT_HANDLE_USERCONTENT:
                switch ($format) {
                    case POP_FORMAT_DETAILS:
                        $layouts[$field_organization] = [GD_URE_Module_Processor_CustomPreviewUserLayouts::class, GD_URE_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS];
                        $layouts[$field_individual] = [GD_URE_Module_Processor_CustomPreviewUserLayouts::class, GD_URE_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS];
                        break;

                    case POP_FORMAT_FULLVIEW:
                        $layouts[$field_organization] = [GD_URE_Module_Processor_CustomFullUserLayouts::class, GD_URE_Module_Processor_CustomFullUserLayouts::COMPONENT_LAYOUT_FULLUSER_ORGANIZATION];
                        $layouts[$field_individual] = [GD_URE_Module_Processor_CustomFullUserLayouts::class, GD_URE_Module_Processor_CustomFullUserLayouts::COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL];
                        break;
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_CommonUserRoles_Multilayout_Processor();
