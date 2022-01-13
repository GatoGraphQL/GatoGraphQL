<?php

\PoP\Root\App::getHookManager()->addFilter('PoP_Module_Processor_UserTypeaheadComponentFormInputsBase:thumbprint-query', 'addProfileRole');
function addProfileRole($query)
{
    $query['role'] = GD_ROLE_PROFILE;
    return $query;
}
