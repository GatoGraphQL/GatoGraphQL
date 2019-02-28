<?php
namespace PoP\Engine;

abstract class DataStructureFormatterBase
{
    public function __construct()
    {
        $datastructureformat_manager = DataStructureFormat_Manager_Factory::getInstance();
        $datastructureformat_manager->add($this->getName(), $this);
    }
    
    abstract public function getName();
    
    public function getFormattedData($data)
    {
        return $data;
    }
    
    public function getJsonEncodeType()
    {
        return null;
    }
    
    public function getDataitem($data_fields, $resultitem, $fieldprocessor)
    {
        $fieldprocessor_name = $fieldprocessor->getName();
        
        $dataitem = array();
        foreach ($data_fields as $field) {
            $value = $fieldprocessor->getValue($resultitem, $field);

            // Comment Leo 29/08/2014: needed for compatibility with Dataloader_ConvertiblePostList
            // (So that data-fields aimed for another post_type are not retrieved)
            if (!is_wp_error($value)) {
                $dataitem[$field] = $value;
            }
        }
        
        return $dataitem;
    }
    
    // Add dataitem to dataset
    public function addToDataitems(&$dataitems, $id, $data_fields, $resultitem, $fieldprocessor)
    {
        $dataitem = $this->getDataitem($data_fields, $resultitem, $fieldprocessor);
        
        // Place under the ID, so it can be found in the database
        $dataitems[$id] = $dataitem;

        return $dataitem;
    }
}
