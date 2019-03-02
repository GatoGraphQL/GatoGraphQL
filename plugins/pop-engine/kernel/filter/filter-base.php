<?php
namespace PoP\Engine;

abstract class FilterBase
{
    public function __construct()
    {
        global $filter_manager;
        $filter_manager->add($this);
    }

    abstract public function getName();
    
    abstract public function getFiltercomponents();

    public function getWildcardFilter()
    {
        return null;
    }

    public function filterQuery(&$query, $data_properties)
    {
    }


    public function getFilterArgsOverrideValues()
    {
        return array();
    }

    public function getFilterArgs()
    {
        return array();
    }
}
