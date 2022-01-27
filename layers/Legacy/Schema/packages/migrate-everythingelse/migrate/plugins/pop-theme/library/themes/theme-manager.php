<?php
namespace PoP\Theme\Themes;

class ThemeManager
{
    public $selected_theme;
    public $themes;

    public function __construct()
    {
        ThemeManagerFactory::setInstance($this);
        $this->themes = array();
        \PoP\Root\App::addAction(
            'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
            array($this, 'init')
        );
    }

    public function addTheme($theme)
    {
        $this->themes[$theme->getName()] = $theme;
    }

    public function init()
    {

        // Selected comes in URL param 'theme'
        $selected = \PoP\Root\App::query(GD_URLPARAM_THEME);

        // Check if the selected theme is inside $themes
        if (!$selected || !in_array($selected, array_keys($this->themes))) {
            $selected = $this->getDefaultThemename();
        }

        $this->selected_theme = $selected;
    }

    public function getDefaultThemename()
    {
        return \PoP\Root\App::applyFilters('\PoP\Theme\Themes\ThemeManager:default', null);
        ;
    }

    public function getTheme($themename = '')
    {
        if (!$themename) {
            $themename = $this->selected_theme;
        }

        return $this->themes[$themename];
    }

    public function getThememode()
    {
        if ($theme = $this->getTheme()) {
            return $theme->getThememode();
        }

        return '';
    }

    public function getThemestyle()
    {
        if ($theme = $this->getTheme()) {
            return $theme->getThemestyle();
        }

        return '';
    }

    public function isDefaultTheme()
    {
        return $this->selected_theme == $this->getDefaultThemename();
    }

    public function getDefaultThememodename($themename)
    {
        if ($theme = $this->getTheme($themename)) {
            return $theme->getDefaultThememodename();
        }

        return null;
    }

    public function isDefaultThememode()
    {
        if ($theme = $this->getTheme()) {
            return $theme->getThememode()->getName() == $theme->getDefaultThememodename();
        }

        return false;
    }

    public function isDefaultThemestyle()
    {
        if ($theme = $this->getTheme()) {
            return $theme->getThemestyle()->getName() == $theme->getDefaultThemestylename();
        }

        return false;
    }

    public function getThemePath()
    {
        if ($theme = $this->getTheme()) {
            // Comment Leo 06/10/2015: Instead of calling function `get_theme_basedir`, use a hook to determine the folder with the templates for the selected theme
            // 2 reasons for this:
            // #1.     Since splitting into poptheme-wassup and poptheme-wassup-webplatform, the logic goes in the 1st but the actual templates in the latter, and the 1st doesn't know which the latter will be
            // #2.     It allows the templates to be overriden
            return ThemeManagerUtils::getThememodeTemplatesDir($theme->getName(), $theme->getThememode()->getName());
        }

        return null;
    }
}

/**
 * Initialization
 */
new ThemeManager();
