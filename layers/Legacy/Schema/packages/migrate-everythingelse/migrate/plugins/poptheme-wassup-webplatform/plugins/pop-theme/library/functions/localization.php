<?php
use PoP\ComponentModel\State\ApplicationState;

\PoP\Root\App::addFilter('gd_jquery_constants', 'popthemeWassupJqueryConstants');
function popthemeWassupJqueryConstants($jqueryConstants)
{
    $jqueryConstants['URLPARAM_THEMEMODE'] = GD_URLPARAM_THEMEMODE;
    $jqueryConstants['URLPARAM_THEMESTYLE'] = GD_URLPARAM_THEMESTYLE;

    $jqueryConstants['THEMEMODE_WASSUP_EMBED'] = GD_THEMEMODE_WASSUP_EMBED;
    $jqueryConstants['THEMEMODE_WASSUP_PRINT'] = GD_THEMEMODE_WASSUP_PRINT;

    $jqueryConstants['THEMESTYLE'] = \PoP\Root\App::getState('themestyle-isdefault') ? '' : \PoP\Root\App::getState('themestyle');
    return $jqueryConstants;
}
