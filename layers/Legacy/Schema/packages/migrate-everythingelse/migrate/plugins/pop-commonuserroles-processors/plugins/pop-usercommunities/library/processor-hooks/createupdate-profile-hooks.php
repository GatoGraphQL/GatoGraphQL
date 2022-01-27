<?php

class PoP_CommonUserRolesProcessors_UserCommunities_CreateUpdateProfileHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'GD_CommonUserRole_UserCommunities_CreateUpdate_ProfileOrganization:form-inputs',
            array($this, 'getFormInputs')
        );
    }

    public function getFormInputs($inputs = array())
    {
        return array_merge(
            $inputs,
            array(
                'is_community' => [GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::class, GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY],
            )
        );
    }
}


/**
 * Initialization
 */
new PoP_CommonUserRolesProcessors_UserCommunities_CreateUpdateProfileHooks();
