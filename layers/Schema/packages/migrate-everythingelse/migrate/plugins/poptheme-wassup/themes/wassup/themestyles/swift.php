<?php

define('GD_THEMESTYLE_WASSUP_SWIFT', 'swift');

class GD_ThemeMode_Wassup_Swift extends GD_WassupThemeStyle_Base
{
    public function getName(): string
    {
        return GD_THEMESTYLE_WASSUP_SWIFT;
    }
}

/**
 * Initialization
 */
new GD_ThemeMode_Wassup_Swift();
