<?php
namespace PoP\Engine;

abstract class FieldProcessor_HookBase
{

    /**
     * Function to override
     */
    abstract public function getFieldprocessorsToHook();

    public function __construct()
    {
        foreach ($this->getFieldprocessorsToHook() as $fieldprocessor_name) {
            $filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FIELDVALUEFILTER, $fieldprocessor_name);
            addFilter($filter, array($this, 'hookValue'), $this->getPriority(), 4);

            $filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FIELDDATALOADERFILTER, $fieldprocessor_name);
            addFilter($filter, array($this, 'hookFieldDefaultDataloader'), $this->getPriority(), 3);
        }
    }

    public function getPriority()
    {
        return 10;
    }

    public function hookValue($value, $resultitem, $field, $fieldprocessor)
    {

        // if $value already is not an error, then another filter already could resolve this field, so return it
        if (!is_wp_error($value)) {
            return $value;
        }

        return $this->getValue($resultitem, $field, $fieldprocessor);
    }

    public function hookFieldDefaultDataloader($default_dataloader, $field, $fieldprocessor)
    {

        // if $value already is not an error, then another filter already could resolve this field, so return it
        if ($default_dataloader) {
            return $default_dataloader;
        }

        return $this->getFieldDefaultDataloader($field, $fieldprocessor);
    }

    public function getValue($resultitem, $field, $fieldprocessor)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $error_class = $cmsapi->getErrorClass();
        return new $error_class('no-field');
    }

    public function getFieldType()
    {
        return GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_DBDATA;
    }

    public function getFieldDefaultDataloader($field, $fieldprocessor)
    {
        return null;
    }
}
