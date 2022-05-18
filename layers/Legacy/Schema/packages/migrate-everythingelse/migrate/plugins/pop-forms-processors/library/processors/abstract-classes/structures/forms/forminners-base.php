<?php

abstract class PoP_Module_Processor_FormInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORM_INNER];
    }

    // function getModuleCbActions(array $componentVariation, array &$props) {
    
    //     // The form inner module, execute it only when doing init-lazy, eg: Edit Individual Profile
    //     // Otherwise do not re-merge it, no need for the form
    //     return array(
    //         GD_MODULECALLBACK_ACTION_LOADCONTENT,
    //         GD_MODULECALLBACK_ACTION_REFETCH,
    //         GD_MODULECALLBACK_ACTION_RESET,
    //     );
    // }
}
