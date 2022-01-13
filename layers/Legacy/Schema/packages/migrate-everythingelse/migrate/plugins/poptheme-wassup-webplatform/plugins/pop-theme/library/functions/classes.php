<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/*
 * Add extra classes to the body: Theme
 */
\PoP\Root\App::getHookManager()->addFilter("gdClassesBody", 'gdClassesBodyThemeImpl');
function gdClassesBodyThemeImpl($body_classes)
{
    $body_classes[] = \PoP\Root\App::getState('theme');
    $body_classes[] = \PoP\Root\App::getState('thememode');
    $body_classes[] = \PoP\Root\App::getState('themestyle');
    $body_classes[] = \PoP\Root\App::getState('theme').'-'.\PoP\Root\App::getState('thememode');
    $body_classes[] = \PoP\Root\App::getState('theme').'-'.\PoP\Root\App::getState('themestyle');
    $body_classes[] = \PoP\Root\App::getState('theme').'-'.\PoP\Root\App::getState('thememode').'-'.\PoP\Root\App::getState('themestyle');
    
    return $body_classes;
}
