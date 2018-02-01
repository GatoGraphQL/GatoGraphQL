<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_Multidomain_FileReproduction_Styles extends PoP_Engine_CSSFileReproductionBase {

    protected $domain;

    function setDomain($domain) {

        $this->domain = $domain;
    }

    function getDomain() {

        return $this->domain ?? get_site_url();
    }

    function get_renderer() {

        global $popthemewassup_multidomainstyles_filerenderer;
        return $popthemewassup_multidomainstyles_filerenderer;
    }

    public function get_assets_path() {
        
        return dirname(__FILE__).'/assets/css/multidomain.css';
    }

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        $domain = $this->getDomain();
        $domain_bgcolors = PoPTheme_Wassup_MultiDomain_Utils::get_multidomain_bgcolors();
        $configuration['{{$domainId}}'] = GD_TemplateManager_Utils::get_domain_id($domain);
        $configuration['{{$backgroundColor}}'] = $domain_bgcolors[$domain];

        return $configuration;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Multidomain_FileReproduction_Styles();
