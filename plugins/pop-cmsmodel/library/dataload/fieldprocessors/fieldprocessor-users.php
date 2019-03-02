<?php
namespace PoP\CMSModel;

define('GD_DATALOAD_FIELDPROCESSOR_USERS', 'users');
 
class FieldProcessor_Users extends \PoP\Engine\FieldProcessorBase
{
    public function getName()
    {
        return GD_DATALOAD_FIELDPROCESSOR_USERS;
    }
    
    public function getValue($resultitem, $field)
    {
    
        // First Check if there's a hook to implement this field
        $hookValue = $this->getHookValue(GD_DATALOAD_FIELDPROCESSOR_USERS, $resultitem, $field);
        if (!is_wp_error($hookValue)) {
            return $hookValue;
        }
                    
        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $user = $resultitem;
        switch ($field) {
            case 'role':
                $user_roles = $cmsresolver->getUserRoles($user);

                // Allow to hook for URE: Make sure we always get the most specific role
                // Otherwise, users like Leo get role 'administrator'
                $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('FieldProcessor_Users:getValue:role', array_shift($user_roles), $this->getId($user));
                break;
            
            case 'username':
                $value = $cmsresolver->getUserLogin($user);
                break;

            case 'user-nicename':
            case 'nicename':
                $value = $cmsresolver->getUserNicename($user);
                break;

            case 'name':
            case 'display-name':
                $value = esc_attr($cmsresolver->getUserDisplayName($user));
                break;

            case 'firstname':
                $value = esc_attr($cmsresolver->getUserFirstname($user));
                break;

            case 'lastname':
                $value = esc_attr($cmsresolver->getUserLastname($user));
                break;

            case 'email':
                $value = $cmsresolver->getUserEmail($user);
                break;
        
            case 'url':
                $value = $cmsapi->getAuthorPostsUrl($this->getId($user));
                break;

            case 'endpoint':
                $value = \PoP\Engine\APIUtils::getEndpoint($this->getValue($resultitem, 'url'));
                break;

            case 'description':
                $value = $cmsresolver->getUserDescription($user);
                break;

            case 'user-url':
                $value = $cmsresolver->getUserUrl($user);
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
        $user = $resultitem;
        return $cmsresolver->getUserId($user);
    }

    public function getFieldDefaultDataloader($field)
    {

        // First Check if there's a hook to implement this field
        $default_dataloader = $this->getHookFieldDefaultDataloader(GD_DATALOAD_FIELDPROCESSOR_USERS, $field);
        if ($default_dataloader) {
            return $default_dataloader;
        }

        return parent::getFieldDefaultDataloader($field);
    }
}

/**
 * Initialize
 */
new FieldProcessor_Users();
