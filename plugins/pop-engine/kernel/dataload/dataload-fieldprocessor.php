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
        return new \PoP\Engine\Error('no-field');
    }

    public function getHookValue($fieldprocessor_name, $resultitem, $field)
    {

        // First Check if there's a hook to implement this field
        $filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FIELDVALUEFILTER, $fieldprocessor_name);
        
        // Also send the fieldprocessor along, as to get the id of the $resultitem being passed
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters($filter, new \PoP\Engine\Error('no-field'), $resultitem, $field, $this);
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
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters($filter, null, $field, $this);
    }
}
