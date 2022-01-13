<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class GD_UserCommunitiesProcessors_CreateUpdate_ProfileHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'GD_UserCommunities_MyCommunitiesUtils:form-inputs',
            array($this, 'getFormInputs')
        );
    }

    public function getFormInputs($inputs = array())
    {
        return array_merge(
            $inputs,
            array(
                'communities' => [GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES],
            )
        );
    }
}


/**
 * Initialization
 */
new GD_UserCommunitiesProcessors_CreateUpdate_ProfileHooks();
