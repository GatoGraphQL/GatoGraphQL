<?php
namespace PoP\Theme\Themes;

abstract class ThemeBase
{
    public $thememodes;
    public $themestyles;

    public function __construct()
    {
        $this->thememodes = array();
        $this->themestyles = array();

        $thememanager = ThemeManagerFactory::getInstance();
        $thememanager->addTheme($this);
    }

    public function addThememode($thememode)
    {
        $this->thememodes[$thememode->getName()] = $thememode;
    }

    public function addThemestyle($themestyle)
    {
        $this->themestyles[$themestyle->getName()] = $themestyle;
    }

    abstract public function getName(): string;

    public function getThememodes()
    {
        return $this->thememodes;
    }

    public function getThemestyles()
    {
        return $this->themestyles;
    }

    public function getDefaultThememodename()
    {
        return null;
    }

    public function getDefaultThemestylename()
    {
        return null;
    }

    public function getThememode()
    {
        $selected = \PoP\Root\App::query(GD_URLPARAM_THEMEMODE);

        if (!$selected || !isset($this->thememodes[$selected])) {
            $selected = $this->getDefaultThememodename();
        }

        return $this->thememodes[$selected];
    }

    public function getThemestyle()
    {
        $selected = \PoP\Root\App::query(GD_URLPARAM_THEMESTYLE);

        if (!$selected || !isset($this->themestyles[$selected])) {
            $selected = $this->getDefaultThemestylename();
        }

        return $this->themestyles[$selected];
    }
}
