<?php

\PoP\Root\App::getHookManager()->addFilter('UserSelectableTypeaheadFormInputs:components:profiles', 'gdUreGetForminputtypeaheadComponents');
function gdUreGetForminputtypeaheadComponents($components)
{
    return array(
        [PoP_CommonUserRoles_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_CommonUserRoles_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION],
        [PoP_CommonUserRoles_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_CommonUserRoles_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL],
    );
}
