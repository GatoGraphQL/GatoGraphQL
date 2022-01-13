<?php

/**
 * User IDs
 */

function getWhoweareCoreUserIds()
{
    return \PoP\Root\App::getHookManager()->applyFilters('getWhoweareCoreUserIds', array());
}
