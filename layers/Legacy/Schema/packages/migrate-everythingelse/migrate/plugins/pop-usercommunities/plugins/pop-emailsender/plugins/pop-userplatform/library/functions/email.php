<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Create / Update Post
 */

// Send an email to the new Communities: when there's a new user
HooksAPIFacade::getInstance()->addAction('gd_createupdate_profile:additionalsCreate', 'gdUreSendemailCreateuser', 100, 2);
function gdUreSendemailCreateuser($user_id, $form_data)
{
    gdUreSendemailCommunityNewmember($user_id, $form_data['communities']);
}
