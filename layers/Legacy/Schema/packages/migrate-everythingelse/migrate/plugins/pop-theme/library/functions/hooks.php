<?php

use PoP\ComponentModel\HelperServices\RequestHelperService;

\PoP\Root\App::addFilter(RequestHelperService::HOOK_CURRENT_URL_REMOVE_PARAMS, 'popThemeRemoveUrlparams');
function popThemeRemoveUrlparams($remove_params)
{
    $remove_params[] = GD_URLPARAM_THEME;
    $remove_params[] = GD_URLPARAM_THEMEMODE;
    $remove_params[] = GD_URLPARAM_THEMESTYLE;

    return $remove_params;
}
