<?php

class PoPTheme_Wassup_Multidomain_EM_FileReproduction_Styles extends PoPTheme_Wassup_Multidomain_FileReproduction_Styles
{
    public function getAssetsPath(): string
    {
        return dirname(__FILE__).'/assets/css/multidomain.css';
    }
}

/**
 * Initialize
 */
new PoPTheme_Wassup_Multidomain_EM_FileReproduction_Styles();
