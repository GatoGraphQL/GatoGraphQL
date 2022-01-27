<?php

define('GD_URLPARAM_URECONTENTSOURCE', 'source');
define('GD_URLPARAM_URECONTENTSOURCE_USER', 'user');
define('GD_URLPARAM_URECONTENTSOURCE_COMMUNITY', 'community');

function gdUreGetDefaultContentsource()
{
    return \PoP\Root\App::applyFilters('gdUreGetDefaultContentsource', GD_URLPARAM_URECONTENTSOURCE_COMMUNITY);
}
