<?php
namespace PoP\Engine;

abstract class ConvertibleFieldProcessorBase extends FieldProcessorBase
{
    protected $fieldprocessor_resolvers;

    public function __construct()
    {
        parent::__construct();
        $this->fieldprocessor_resolvers = array();
    }

    public function addFieldprocessorResolver($fieldprocessor_resolver)
    {

        // Important: add the newer resolvers at the beginning, and not at the end
        // This is because plugins in PoP are initialized by priority, so that more general plugins (eg: Users) are defined first,
        // and dependent plugins (eg: Communities, Organizations) are defined later
        // Then, more specific implementations (eg: Organizations) must be queried before more general ones (eg: Communities)
        // This is not a problem by making the corresponding field processors inherit from each other, so that the more specific object also handles
        // the fields for the more general ones (eg: GD_DataLoad_FieldProcessor_OrganizationUsers extends GD_DataLoad_FieldProcessor_CommunityUsers, and GD_DataLoad_FieldProcessor_CommunityUsers extends GD_DataLoad_FieldProcessor_Users)
        array_unshift($this->fieldprocessor_resolvers, $fieldprocessor_resolver);
        // $this->fieldprocessor_resolvers[] = $fieldprocessor_resolver;
    }

    protected function getDefaultFieldprocessor()
    {
        return null;
    }

    protected function getFieldprocessor($resultitem)
    {

        // Among all registered fieldprocessors, check if any is able to process the object, through function `process`
        foreach ($this->fieldprocessor_resolvers as $maybe_fieldprocessor_resolver) {
            if ($maybe_fieldprocessor_resolver->process($resultitem)) {
                // Found it!
                $fieldprocessor_resolver = $maybe_fieldprocessor_resolver;
                $fieldprocessor_name = $fieldprocessor_resolver->getFieldprocessor();
                break;
            }
        }

        // If none found, use the default one
        $fieldprocessor_name = $fieldprocessor_name ?? $this->getDefaultFieldprocessor();

        // From the fieldprocessor name, return the object
        $fieldprocessor_manager = FieldProcessor_Manager_Factory::getInstance();
        $fieldprocessor = $fieldprocessor_manager->get($fieldprocessor_name);

        // Return also the resolver, as to cast the object
        return array($fieldprocessor, $fieldprocessor_resolver);
    }
    
    public function getValue($resultitem, $field)
    {

        // Delegate to the Fieldprocessor corresponding to this object
        list($fieldprocessor, $fieldprocessor_resolver) = $this->getFieldprocessor($resultitem);

        // Cast object, eg from post to event
        if ($fieldprocessor_resolver) {
            $resultitem = $fieldprocessor_resolver->cast($resultitem);
        }

        // Delegate to that fieldprocessor to obtain the value
        return $fieldprocessor->getValue($resultitem, $field);
    }

    public function getFieldDefaultDataloader($field)
    {

        // Please notice that we're getting the default dataloader from the default fieldprocessor
        if ($default_fieldprocessor_name = $this->getDefaultFieldprocessor()) {
            $fieldprocessor_manager = FieldProcessor_Manager_Factory::getInstance();
            $default_fieldprocessor = $fieldprocessor_manager->get($default_fieldprocessor_name);
            $default_dataloader = $default_fieldprocessor->getFieldDefaultDataloader($field);
            if ($default_dataloader) {
                return $default_dataloader;
            }
        }

        return parent::getFieldDefaultDataloader($field);
    }
}
