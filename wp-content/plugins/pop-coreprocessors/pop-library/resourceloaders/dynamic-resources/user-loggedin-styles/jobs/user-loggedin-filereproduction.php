<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CoreProcessors_FileReproduction_UserLoggedInStyles extends PoP_Engine_CSSFileReproductionBase {

    protected $domain;

    function setDomain($domain) {

        $this->domain = $domain;
    }

    function getDomain() {

        return $this->domain ?? get_site_url();
    }

    function get_renderer() {

        global $popcore_userloggedinstyles_filerenderer;
        return $popcore_userloggedinstyles_filerenderer;
    }

    public function get_assets_path() {
        
        return dirname(__FILE__).'/assets/css/user-loggedin.css';
    }

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        $configuration['{{$domainId}}'] = GD_TemplateManager_Utils::get_domain_id($this->getDomain());

        return $configuration;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_FileReproduction_UserLoggedInStyles();
