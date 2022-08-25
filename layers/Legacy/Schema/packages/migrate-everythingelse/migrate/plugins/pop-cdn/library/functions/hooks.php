<?php

use PoP\ComponentModel\HelperServices\RequestHelperService;

\PoP\Root\App::addFilter(RequestHelperService::HOOK_CURRENT_URL_REMOVE_PARAMS, 'popCdnRemoveUrlparams');
function popCdnRemoveUrlparams($remove_params)
{
    $remove_params[] = GD_URLPARAM_CDNTHUMBPRINT;

    return $remove_params;
}
