<?php
namespace PoP\CMSModel;

define('GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS', 'menu-items');

class FieldProcessor_Menu_Items extends \PoP\Engine\FieldProcessorBase
{
    public function getName()
    {
        return GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS;
    }

    public function getValue($resultitem, $field)
    {

        // First Check if there's a hook to implement this field
        $hookValue = $this->getHookValue(GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS, $resultitem, $field);
        if (!is_wp_error($hookValue)) {
            return $hookValue;
        }
    
        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $menu_item = $resultitem;
        switch ($field) {
        
        case 'title':
            $value = apply_filters('the_title', $cmsresolver->getMenuItemTitle($menu_item), $cmsresolver->getMenuItemObjectId($menu_item));
            break;

        case 'alt':
            $value = $cmsresolver->getMenuItemTitle($menu_item);
            break;
        
        case 'url':
            $value = $cmsresolver->getMenuItemUrl($menu_item);
            break;
        
        case 'classes':
                
            // Copied from nav-menu-template.php function start_el
            $classes = $cmsresolver->getMenuItemClasses($menu_item);
            $classes = empty($classes) ? array() : (array) $classes;
            $classes[] = 'menu-item';
            $classes[] = 'menu-item-' . $cmsresolver->getMenuItemId($menu_item);
            if ($parent = $cmsresolver->getMenuItemParent($menu_item)) {
                $classes[] = 'menu-item-parent';
                $classes[] = 'menu-item-parent-' . $parent;
            }
            $value = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $menu_item, array()));
            break;
            
        case 'target':
            $value = $cmsresolver->getMenuItemTarget($menu_item);
            break;

        case 'additional-attrs':
            // Using the description, because WP does not give a field for extra attributes when creating a menu,
            // and this is needed to add target="addons" for the Add ContentPost link
            $value = $cmsresolver->getMenuItemDescription($menu_item);
            break;

        case 'object-id':
            $value = $cmsresolver->getMenuItemObjectId($menu_item);
            break;

        case 'menu-item-parent':
            $value = $cmsresolver->getMenuItemParent($menu_item);
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
        $menu_item = $resultitem;
        return $cmsresolver->getMenuItemId($menu_item);
    }

    public function getFieldDefaultDataloader($field)
    {

        // First Check if there's a hook to implement this field
        $default_dataloader = $this->getHookFieldDefaultDataloader(GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS, $field);
        if ($default_dataloader) {
            return $default_dataloader;
        }

        return parent::getFieldDefaultDataloader($field);
    }
}
    
/**
 * Initialize
 */
new FieldProcessor_Menu_Items();
