<?php

class PoPTheme_Wassup_WSL_FileReproduction_UserLoggedInStyles extends PoP_CoreProcessors_FileReproduction_UserLoggedInStyles
{
    public function getAssetsPath(): string
    {
        return dirname(__FILE__).'/assets/css/user-loggedin.css';
    }
}

/**
 * Initialize
 */
new PoPTheme_Wassup_WSL_FileReproduction_UserLoggedInStyles();
