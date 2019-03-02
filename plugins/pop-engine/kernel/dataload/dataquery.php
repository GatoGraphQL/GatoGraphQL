<?php
namespace PoP\Engine;

abstract class DataQueryBase
{

    // Allow Plugins to inject extra properties. Eg: PoP User Login can inject loggedinuser-fields
    protected $properties;

    public function __construct()
    {
        $this->properties = array();
    
        $dataquery_manager = DataQuery_Manager_Factory::getInstance();
        $dataquery_manager->add($this->getName(), $this);
    }
    
    /**
     * Function to override
     */
    abstract public function getName();

    public function addProperty($name, $value)
    {
        $this->properties[$name] = array_merge(
            $this->properties[$name] ?? array(),
            $value
        );
    }

    public function getProperty($name)
    {
        return $this->properties[$name];
    }
    
    /**
     * Function to override
     */
    public function getNoncacheablePage()
    {
        return null;
    }
    /**
     * Function to override
     */
    public function getCacheablePage()
    {
        return null;
    }
    /**
     * Function to override
     */
    public function getObjectidFieldname()
    {
        return 'id';
    }
    /**
     * What fields can be requested on the outside-looking API to query data. By default: everything that must be loaded from the server
     * and that depends on the logged-in user
     */
    public function getAllowedfields()
    {
        $allowedfields = $this->getNocachefields();
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('Dataquery:'.$this->getName().':allowedfields', $allowedfields);
    }
    /**
     * What fields are to be rejected
     */
    public function getRejectedfields()
    {
        $rejectedfields = array();
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('Dataquery:'.$this->getName().':rejectedfields', $rejectedfields);
    }
    /**
     * What layouts can be requested on the outside-looking API to query data. By default: everything that can be lazy
     */
    public function getAllowedlayouts()
    {
        $allowedlayouts = array();
        foreach ($this->getLazylayouts() as $field => $lazylayouts) {
            foreach ($lazylayouts as $key => $layout) {
                $allowedlayouts[] = $layout;
            }
        }
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('Dataquery:'.$this->getName().':allowedlayouts', array_unique($allowedlayouts));
    }
    /**
     * Fields whose data must be retrieved each single time from the server. Eg: comment-count, since adding a comment doesn't delete the overall cache,
     * so the number cached in html will be out of date
     */
    public function getNocachefields()
    {
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('Dataquery:'.$this->getName().':nocachefields', array());
    }
    /**
     * Fields whose data is retrieved on a subsequent call to the server
     */
    public function getLazylayouts()
    {
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('Dataquery:'.$this->getName().':lazylayouts', array());
    }
}
