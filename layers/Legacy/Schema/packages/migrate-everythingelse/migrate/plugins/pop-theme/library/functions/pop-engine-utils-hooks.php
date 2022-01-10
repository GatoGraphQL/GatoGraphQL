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

        $vars['theme'] = $thememanager->getTheme() ? $thememanager->getTheme()->getName() : '';
        $vars['thememode'] = $thememanager->getThememode() ? $thememanager->getThememode()->getName() : '';
        $vars['themestyle'] = $thememanager->getThemestyle() ? $thememanager->getThemestyle()->getName() : '';
        $vars['theme-isdefault'] = $thememanager->isDefaultTheme();
        $vars['thememode-isdefault'] = $thememanager->isDefaultThememode();
        $vars['themestyle-isdefault'] = $thememanager->isDefaultThemestyle();
        $vars['theme-path'] = $thememanager->getThemePath();
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
