<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('RequestUtils:current_url:remove_params', 'popThemeRemoveUrlparams');
function popThemeRemoveUrlparams($remove_params)
{
    $remove_params[] = GD_URLPARAM_THEME;
    $remove_params[] = GD_URLPARAM_THEMEMODE;
    $remove_params[] = GD_URLPARAM_THEMESTYLE;

    return $remove_params;
}
