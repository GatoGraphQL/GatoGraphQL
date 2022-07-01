<?php

/**
 * Create / Update Post
 */

// Send an email to the new Communities: when there's a new user
\PoP\Root\App::addAction('gd_createupdate_profile:additionalsCreate', 'gdUreSendemailCreateuser', 100, 2);
function gdUreSendemailCreateuser($user_id, WithArgumentsInterface $withArgumentsAST)
{
    gdUreSendemailCommunityNewmember($user_id, $withArgumentsAST->getArgumentValue('communities'));
}
