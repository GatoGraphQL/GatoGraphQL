<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ServiceWorkers_Job
{
    public function __construct()
    {
        global $pop_serviceworkers_job_manager;
        $pop_serviceworkers_job_manager->add($this->getScope(), $this);
    }
    
    /**
     * Function to override
     */
    public function getScope()
    {
        return site_url('/', 'relative');
    }

    // function getSwJsFilename() {

    //     return '';
    // }
    
    // public function getSwJsDir() {
        
    //     return '';
    // }
    
    public function getSwJsPath()
    {
        
        // return $this->getSwJsDir().'/'.$this->getSwJsFilename();
        return '';
    }
    
    public function getDependencies()
    {
        return array();
    }

    public function getSwCodereplacements()
    {
        global $pop_serviceworkers_manager;
        
        // Foldername: where the dependency scripts are located
        $dependencies_foldername = $pop_serviceworkers_manager->getDependenciesFoldername();

        // Path: allow to add the full path to the folder, so we can access them under assets.getpop.org instead of getpop.org (which happens using a relative path)
        // Allow the CDN to be injected by hook
        $dependencies_path = HooksAPIFacade::getInstance()->applyFilters(
            'PoP_ServiceWorkers_Job:dependencies_path',
            POP_SERVICEWORKERS_ASSETDESTINATION_URL.'/'.$dependencies_foldername
        );
        return array(
            '$dependenciesFolder' => $dependencies_foldername,
            '$dependenciesPath' => $dependencies_path,
        );
    }

    public function getSwConfiguration()
    {
        
        return array();
    }
}
