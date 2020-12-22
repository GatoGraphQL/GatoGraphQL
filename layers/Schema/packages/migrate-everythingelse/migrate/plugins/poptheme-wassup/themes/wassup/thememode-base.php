<?php

abstract class GD_WassupThemeMode_Base extends GD_ThemeModeBase
{
    public function getTheme()
    {
        global $gd_theme_mesym;
        return $gd_theme_mesym;
    }
}
