<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_WSL_FileReproduction_UserLoggedInStyles extends PoP_CoreProcessors_FileReproduction_UserLoggedInStyles {

    public function get_assets_path() {
        
        return dirname(__FILE__).'/assets/css/user-loggedin.css';
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_WSL_FileReproduction_UserLoggedInStyles();
