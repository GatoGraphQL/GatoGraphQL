<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// After function `getUserRoleCombinationsCommonroles`
\PoP\Root\App::getHookManager()->addFilter('getUserRoleCombinations', 'getUserRoleCombinationsUsercommunitiesCommonroles', 105);
function getUserRoleCombinationsUsercommunitiesCommonroles($user_role_combinations)
{

    // Add: each Organization may be a Community or not
    $user_role_combinations[] = array(
        GD_ROLE_PROFILE,
        GD_URE_ROLE_ORGANIZATION,
        GD_URE_ROLE_COMMUNITY,
    );
    return $user_role_combinations;
}
