<?php
namespace PoP\CMSModel;

define('GD_DATALOAD_FIELDPROCESSOR_MENU', 'menu');
    
class FieldProcessor_Menu extends \PoP\Engine\FieldProcessorBase
{
    public function getName()
    {
        return GD_DATALOAD_FIELDPROCESSOR_MENU;
    }

    public function getValue($resultitem, $field)
    {

        // First Check if there's a hook to implement this field
        $hookValue = $this->getHookValue(GD_DATALOAD_FIELDPROCESSOR_MENU, $resultitem, $field);
        if (!is_wp_error($hookValue)) {
            return $hookValue;
        }
    
        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $menu = $resultitem;
        switch ($field) {
            case 'items':
                // Load needed values for the menu-items
                $fieldprocessor_manager = \PoP\Engine\FieldProcessor_Manager_Factory::getInstance();
                $gd_dataload_fieldprocessor_menu_items = $fieldprocessor_manager->get(GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS);
                $items = $cmsapi->wpGetNavMenuItems($cmsresolver->getMenuTermId($menu));

                // Load these item data-fields. If other set needed, create another $field
                $item_data_fields = array('id', 'title', 'alt', 'classes', 'url', 'target', 'menu-item-parent', 'object-id', 'additional-attrs');
                $value = array();
                if ($items) {
                    foreach ($items as $item) {
                        $item_value = array();
                        foreach ($item_data_fields as $item_data_field) {
                            $item_value[$item_data_field] = $gd_dataload_fieldprocessor_menu_items->getValue($item, $item_data_field);
                        }

                        $id = $gd_dataload_fieldprocessor_menu_items->getId($item);
                        $value[] = $item_value;
                    }
                }
                break;
            
            default:
                $value = parent::getValue($resultitem, $field);
                break;
        }

        return $value;
    }

    public function getId($resultitem)
    {
        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $menu = $resultitem;
        return $cmsresolver->getMenuTermId($menu);
    }

    public function getFieldDefaultDataloader($field)
    {

        // First Check if there's a hook to implement this field
        $default_dataloader = $this->getHookFieldDefaultDataloader(GD_DATALOAD_FIELDPROCESSOR_MENU, $field);
        if ($default_dataloader) {
            return $default_dataloader;
        }

        return parent::getFieldDefaultDataloader($field);
    }
}

/**
 * Initialize
 */
new FieldProcessor_Menu();
