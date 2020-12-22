<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

/*
 * Add extra classes to the body: Theme
 */
HooksAPIFacade::getInstance()->addFilter("gdClassesBody", 'gdClassesBodyThemeImpl');
function gdClassesBodyThemeImpl($body_classes)
{
    $vars = ApplicationState::getVars();
    $body_classes[] = $vars['theme'];
    $body_classes[] = $vars['thememode'];
    $body_classes[] = $vars['themestyle'];
    $body_classes[] = $vars['theme'].'-'.$vars['thememode'];
    $body_classes[] = $vars['theme'].'-'.$vars['themestyle'];
    $body_classes[] = $vars['theme'].'-'.$vars['thememode'].'-'.$vars['themestyle'];
    
    return $body_classes;
}
