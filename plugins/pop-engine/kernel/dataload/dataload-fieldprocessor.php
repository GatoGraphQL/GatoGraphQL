<?php
namespace PoP\Engine;

abstract class FieldProcessorBase
{
    public function __construct()
    {
        $fieldprocessor_manager = FieldProcessor_Manager_Factory::getInstance();
        $fieldprocessor_manager->add($this->getName(), $this);
    }

    public function getId($resultitem)
    {
        return $resultitem->ID;
    }
    
    public function getValue($resultitem, $field)
    {
        switch ($field) {
        
        case 'id':
            
            return $this->getId($resultitem);
        }

        // Comment Leo 29/08/2014: needed for compatibility with Dataloader_ConvertiblePostList
        // (So that data-fields aimed for another post_type are not retrieved)
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $error_class = $cmsapi->getErrorClass();
        return new $error_class('no-field');
    }

    public function getHookValue($fieldprocessor_name, $resultitem, $field)
    {

        // First Check if there's a hook to implement this field
        $filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FIELDVALUEFILTER, $fieldprocessor_name);
        
        // Also send the fieldprocessor along, as to get the id of the $resultitem being passed
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $error_class = $cmsapi->getErrorClass();
        return apply_filters($filter, new $error_class('no-field'), $resultitem, $field, $this);
    }
    
    abstract public function getName();

    public function getFieldDefaultDataloader($field)
    {
        return null;
    }

    public function getHookFieldDefaultDataloader($fieldprocessor_name, $field)
    {

        // First Check if there's a hook to implement this field
        $filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FIELDDATALOADERFILTER, $fieldprocessor_name);
        return apply_filters($filter, null, $field, $this);
    }
}
