<?php

/**
 * user meta
 */

function gdGetUserShortdescription($user_id)
{
    return \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, true);
}
