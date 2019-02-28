<?php
namespace PoP\Engine;

class ConvertibleFieldProcessorResolverBase
{
    public function __construct($convertiblefieldprocessor_name)
    {
        $fieldprocessor_manager = FieldProcessor_Manager_Factory::getInstance();
        $convertiblefieldprocessor = $fieldprocessor_manager->get($convertiblefieldprocessor_name);
        $convertiblefieldprocessor->addFieldprocessorResolver($this);
    }

    public function getFieldprocessor()
    {
        return null;
    }

    public function process($resultitem)
    {
        return false;
    }

    public function cast($resultitem)
    {
        return $resultitem;
    }
}
