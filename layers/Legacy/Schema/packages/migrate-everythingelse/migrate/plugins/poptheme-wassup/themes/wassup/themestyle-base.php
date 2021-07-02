<?php

abstract class GD_WassupThemeStyle_Base extends \PoP\Theme\Themes\ThemeStyleBase
{
    public function getTheme()
    {
        global $gd_theme_mesym;
        return $gd_theme_mesym;
    }
}
