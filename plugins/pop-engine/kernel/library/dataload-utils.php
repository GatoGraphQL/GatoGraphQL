<?php
namespace PoP\Engine;

class DataloadUtils
{
    public static function getDefaultDataloaderNameFromSubcomponentDataField($dataloader_or_name, $subcomponent_data_field)
    {
        if (is_object($dataloader_or_name)) {
            $dataloader = $dataloader_or_name;
        } else {
            $dataloader_name = $dataloader_or_name;
            $dataloader_manager = Dataloader_Manager_Factory::getInstance();
            $dataloader = $dataloader_manager->get($dataloader_name);
        }

        $fieldprocessor_manager = FieldProcessor_Manager_Factory::getInstance();
        $fieldprocessor_name = $dataloader->getFieldprocessor();
        $fieldprocessor = $fieldprocessor_manager->get($fieldprocessor_name);
        $subcomponent_dataloader_name = $fieldprocessor->getFieldDefaultDataloader($subcomponent_data_field);
        if (!$subcomponent_dataloader_name && \PoP\Engine\Server\Utils::failIfSubcomponentDataloaderUndefined()) {
            throw new \Exception(sprintf('There is no default dataloader set for field  "%s" from dataloader "%s" and fieldprocessor "%s" (%s)', $subcomponent_data_field, $dataloader_name, $fieldprocessor_name, fullUrl()));
        }

        return $subcomponent_dataloader_name;
    }
    
    public static function addFilterParams($url, $moduleValues = array())
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $args = [];
        foreach ($moduleValues as $module => $value) {
            if (in_array($module, [
                POP_MODULE_FILTERINPUT_SEARCH,
                POP_MODULE_FILTERINPUT_POSTDATES,
            ])) {

                $moduleprocessor = $moduleprocessor_manager->getProcessor($module);
                $args[$moduleprocessor->getName($module)] = $value;
            }
        }
        $cmshelpers = \PoP\CMS\HelperAPI_Factory::getInstance();
        $cmshelpers->addQueryArgs($args, $url);
        
        return $url;
    }
}
