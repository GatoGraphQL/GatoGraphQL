<?php
namespace PoP\Engine;

class DataQuery_HookBase
{
    public function __construct()
    {
        $name = $this->getDataqueryName();
        addFilter('Dataquery:'.$name.':allowedfields', array($this, 'addAllowedfields'));
        addFilter('Dataquery:'.$name.':rejectedfields', array($this, 'addRejectedfields'));
        addFilter('Dataquery:'.$name.':allowedlayouts', array($this, 'addAllowedlayouts'));
        addFilter('Dataquery:'.$name.':nocachefields', array($this, 'addNocachefields'));
        addFilter('Dataquery:'.$name.':lazylayouts', array($this, 'addLazylayouts'));
    }
    public function getDataqueryName()
    {
        return '';
    }
    public function addAllowedfields($allowedfields)
    {
        return array_unique(
            array_merge(
                $allowedfields,
                $this->getAllowedfields()
            )
        );
    }
    public function addRejectedfields($rejectedfields)
    {
        return array_unique(
            array_merge(
                $rejectedfields,
                $this->getRejectedfields()
            )
        );
    }
    public function addAllowedlayouts($allowedlayouts)
    {
        return array_unique(
            array_merge(
                $allowedlayouts,
                $this->getAllowedlayouts()
            )
        );
    }
    public function addNocachefields($nocachefields)
    {
        return array_merge(
            $nocachefields,
            $this->getNocachefields()
        );
    }
    public function addLazylayouts($lazylayouts)
    {
        return array_merge(
            $lazylayouts,
            $this->getLazylayouts()
        );
    }

    public function getAllowedfields()
    {
        return $this->getNocachefields();
    }
    public function getRejectedfields()
    {
        return array();
    }
    public function getAllowedlayouts()
    {
        $allowedlayouts = array();
        foreach ($this->getLazylayouts() as $field => $lazylayouts) {
            foreach ($lazylayouts as $key => $layout) {
                $allowedlayouts[] = $layout;
            }
        }
        return array_unique($allowedlayouts);
    }
    

    /**
     * Implement functions below in the hook implementation
     */
    public function getNocachefields()
    {
        return array();
    }
    public function getLazylayouts()
    {
        return array();
    }
}
