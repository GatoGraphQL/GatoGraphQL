<?php
namespace PoP\Engine;

abstract class FilterComponentBase
{
    public function getFilterinputValue($filter)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $filterinput = $this->getFilterinput();
        return $moduleprocessor_manager->getProcessor($filterinput)->getValue($filterinput, $filter);
    }

    public function getName()
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $filterinput = $this->getFilterinput();
        return $moduleprocessor_manager->getProcessor($filterinput)->getName($filterinput);
    }
    
    abstract public function getFilterinput();
    
    public function getForminput()
    {
        return $this->getFilterinput();
    }

    public function addForminputModule()
    {
        return true;
    }
    
    public function getMetaqueryKey()
    {
        return null;
    }
    
    public function getMetaqueryCompare()
    {
        return 'IN';
    }
    
    public function getMetaquery($filter)
    {
        $meta_query = array();
        $key = $this->getMetaqueryKey();
        $value = $this->getFilterinputValue($filter);
        $compare = $this->getMetaqueryCompare();
        
        // Special case for EXISTS: it can switch between EXISTS and NOT EXISTS depending on if the value is true or false
        if ($compare == 'EXISTS') {
            // $value can be a single value, or an array of true/false
            if (is_array($value)) {
                // Do the filtering only if there is 1 value (2 values => same as not filtering)
                if (count($value) !== 1) {
                    return $meta_query;
                }

                // Only 1 value in the multiselect, extract it
                $value = $value[0];
            }

            // Do the filtering: if $value is false, then filter by NOT EXISTS
            if ($value === false) {
                $value = true;
                $compare = 'NOT EXISTS';
            }
        }
        
        if ($key && $value) {
            $meta_query[] = array(
                'key' => $key,
                'value' => $value,
                'compare' => $compare
            );
        }
                                
        return $meta_query;
    }
    
    public function getOrder($filter)
    {
        return array();
    }
    
    public function getPostdates($filter)
    {
        return null;
    }

    public function getAuthor($filter)
    {
        return array();
    }

    public function getPoststatus($filter)
    {
        return array();
    }
    
    public function getSearch($filter)
    {
        return null;
    }

    public function getTags($filter)
    {
        return array();
    }

    public function getCategories($filter)
    {
        return array();
    }

    public function getTaxonomies($filter)
    {
        return array();
    }
}
