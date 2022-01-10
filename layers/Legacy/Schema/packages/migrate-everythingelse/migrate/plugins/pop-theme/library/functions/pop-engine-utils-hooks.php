<?php
namespace PoP\Theme;

use PoP\Engine\FieldResolvers\ObjectType\OperatorGlobalObjectTypeFieldResolver;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Theme_UtilsHooks
{
    /**
     * @todo Migrate to AppStateProvider
     * @param array<array> $vars_in_array
     */
    public static function addVars(array $vars_in_array): void
    {
        $thememanager = Themes\ThemeManagerFactory::getInstance();
        $vars = &$vars_in_array[0];

        \PoP\Root\App::getState('theme') = $thememanager->getTheme() ? $thememanager->getTheme()->getName() : '';
        \PoP\Root\App::getState('thememode') = $thememanager->getThememode() ? $thememanager->getThememode()->getName() : '';
        \PoP\Root\App::getState('themestyle') = $thememanager->getThemestyle() ? $thememanager->getThemestyle()->getName() : '';
        \PoP\Root\App::getState('theme-isdefault') = $thememanager->isDefaultTheme();
        \PoP\Root\App::getState('thememode-isdefault') = $thememanager->isDefaultThememode();
        \PoP\Root\App::getState('themestyle-isdefault') = $thememanager->isDefaultThemestyle();
        \PoP\Root\App::getState('theme-path') = $thememanager->getThemePath();
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function setSafeVars(array $vars_in_array): void
    {
        // Remove the theme path
        $safeVars = &$vars_in_array[0];
        unset($safeVars['theme-path']);
    }
}

/**
 * Initialization
 */
HooksAPIFacade::getInstance()->addAction('ApplicationState:addVars', array(PoP_Theme_UtilsHooks::class, 'addVars'), 10, 1);
HooksAPIFacade::getInstance()->addAction(OperatorGlobalObjectTypeFieldResolver::HOOK_SAFEVARS, array(PoP_Theme_UtilsHooks::class, 'setSafeVars'), 10, 1);
