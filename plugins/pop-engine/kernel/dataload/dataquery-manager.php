<?php
namespace PoP\Engine;

class DataQuery_Manager
{
    public $dataqueries;
    
    public function __construct()
    {
        DataQuery_Manager_Factory::setInstance($this);
        return $this->dataqueries = array();
    }
    
    public function add($name, $dataquery)
    {
        $this->dataqueries[$name] = $dataquery;
    }
    
    public function get($name)
    {
        return $this->dataqueries[$name];
    }

    public function filterAllowedfields($fields)
    {

        // Choose if to reject fields, starting from all of them...
        // By default, if API is enabled, then use this method
        if (\PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('DataQuery_Manager:filter_by_rejection', Server\Utils::enableApi())) {
            return array_values(
                array_diff(
                    $fields,
                    $this->getRejectedfields()
                )
            );
        }

        // ... or choose to allow fields, starting from an empty list
        // Used to restrict access to the site, and only offer those fields needed for lazy loading
        return array_values(
            array_intersect(
                $fields,
                $this->getAllowedfields()
            )
        );
    }

    public function filterAllowedlayouts($layouts)
    {

        // // If allow to return all layouts, then no need to filter them
        // if (\PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('DataQuery_Manager:allow_all_layouts', false)) {

        //     return $layouts;
        // }

        return array_values(
            array_intersect(
                $layouts,
                $this->getAllowedlayouts()
            )
        );
    }

    public function getAllowedfields()
    {
        $allowedfields = array();
        foreach ($this->dataqueries as $name => $dataquery) {
            $allowedfields = array_merge(
                $allowedfields,
                $dataquery->getAllowedfields()
            );
        }

        return array_unique($allowedfields);
    }

    public function getRejectedfields()
    {
        $rejectedfields = array();
        foreach ($this->dataqueries as $name => $dataquery) {
            $rejectedfields = array_merge(
                $rejectedfields,
                $dataquery->getRejectedfields()
            );
        }

        return array_unique($rejectedfields);
    }

    public function getAllowedlayouts()
    {
        $allowedlayouts = array();
        foreach ($this->dataqueries as $name => $dataquery) {
            $allowedlayouts = array_merge(
                $allowedlayouts,
                $dataquery->getAllowedlayouts()
            );
        }

        return array_unique($allowedlayouts);
    }

    public function getCacheablepages()
    {
        $cacheablepages = array();
        foreach ($this->dataqueries as $name => $dataquery) {
            $cacheablepages[] = $dataquery->getCacheablePage();
        }

        return $cacheablepages;
    }

    public function getNoncacheablepages()
    {
        $noncacheablepages = array();
        foreach ($this->dataqueries as $name => $dataquery) {
            $noncacheablepages[] = $dataquery->getNoncacheablePage();
        }

        return $noncacheablepages;
    }
}
    
/**
 * Initialize
 */
new DataQuery_Manager();
