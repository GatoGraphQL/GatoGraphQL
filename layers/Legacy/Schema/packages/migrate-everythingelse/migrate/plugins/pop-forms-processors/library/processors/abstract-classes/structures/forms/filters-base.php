<?php

define('GD_SUBMITFORMTYPE_FILTERBLOCK', 'filterblock');
define('GD_SUBMITFORMTYPE_FILTERBLOCKGROUP', 'filterblockgroup');
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FiltersBase extends PoP_Module_Processor_FormsBase
{
    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Comment Leo 10/12/2017: this function MUST be critical, because it needs to set the filter query args on the block
        // before initializing a lazy block, eg: http://sukipop.localhost/en/search-content/?filter=content&searchfor=change
        $this->addJsmethod($ret, 'initFilter', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);

        // Depending on the form type, execute a js method or another
        $form_type = $this->getFormType($component, $props);
        if ($form_type == GD_SUBMITFORMTYPE_FILTERBLOCK) {
            $this->addJsmethod($ret, 'initBlockFilter');
        } elseif ($form_type == GD_SUBMITFORMTYPE_FILTERBLOCKGROUP) {
            $this->addJsmethod($ret, 'initBlockGroupFilter');
        }
        return $ret;
    }

    public function getFormType(array $component, array &$props)
    {

        // Allow the Block to set the form type (eg: to override FILTERBLOCK with FILTERBLOCKGROUP)
        if ($form_type = $this->getProp($component, $props, 'form-type')) {
            return $form_type;
        }

        // Default: filter the block
        return GD_SUBMITFORMTYPE_FILTERBLOCK;
    }

    public function getMethod(array $component, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no PoP_WebPlatform_ServerUtils
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            // Comment Leo 08/12/2017: if we have no JS, then use 'POST', because otherwise the ?config=disable-js parameter
            // gets dropped away and the search response is, once again, JS-enabled
            // Source: https://stackoverflow.com/questions/732371/what-happens-if-the-action-field-in-a-form-has-parameters
            if (PoP_WebPlatform_ServerUtils::disableJs()) {
                return 'POST';
            }
        }

        return 'GET';
    }
    
    public function fixedId(array $component, array &$props): bool
    {

        // So that it can be collapsed from the ControlGroup
        return true;
    }

    // public function getFilterObject(array $component)
    // {
    //     $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    //     $filterinner = $this->getInnerSubmodule($component);
    //     return $componentprocessor_manager->getProcessor($filterinner)->getFilterObject($filterinner);
    // }
    // public function getFilter(array $component)
    // {
    //     $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    //     $filterinner = $this->getInnerSubmodule($component);
    //     return $componentprocessor_manager->getProcessor($filterinner)->getFilter($filterinner);
    // }
}
