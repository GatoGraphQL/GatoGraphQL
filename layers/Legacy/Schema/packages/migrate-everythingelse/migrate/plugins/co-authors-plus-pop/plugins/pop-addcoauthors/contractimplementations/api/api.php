<?php

use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class CAP_PoP_AddCoauthors_API extends PoP_AddCoauthors_API_Base implements PoP_AddCoauthors_API
{
    public function addCoauthors($post_id, $coauthors)
    {
        global $userdata, $coauthors_plus;
        $userTypeAPI = UserTypeAPIFacade::getInstance();

        // If passing null, reset to an empty array
        $coauthors = $coauthors ?? array();

        $coauthor_nicenames = array();
        foreach ($coauthors as $user_id) {
            $user = $userTypeAPI->getUserById($user_id);
            $coauthor_nicenames[] = $user->user_nicename;
        }

        // If the current user is not there, add it (in first position)
        if (!in_array($userdata->user_nicename, $coauthor_nicenames)) {
            array_unshift($coauthor_nicenames, $userdata->user_nicename);
        }

        $coauthors_plus->add_coauthors($post_id, $coauthor_nicenames);
    }
}

/**
 * Initialize
 */
new CAP_PoP_AddCoauthors_API();
