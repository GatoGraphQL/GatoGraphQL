<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_UserLoggedInsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_UserLogin_TemplateResourceLoaderProcessor::class, PoP_UserLogin_TemplateResourceLoaderProcessor::RESOURCE_USERLOGGEDIN];
    }

    public function addLink(array $module, array &$props)
    {
        return false;
    }

    public function addUseravatar(array $module, array &$props)
    {
        return PoP_Application_ConfigurationUtils::useUseravatar();
    }

    public function getTitleTop(array $module, array &$props)
    {
        return '';
    }

    public function getTitleBottom(array $module, array &$props)
    {
        return '';
    }

    public function getNameHtmlmarkup(array $module, array &$props)
    {
        return 'h2';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($this->addLink($module, $props)) {
            $ret['add-link'] = true;
        }

        if ($title_top = $this->getTitleTop($module, $props)) {
            $ret[GD_JS_TITLES]['top'] = $title_top;
        }
        if ($title_bottom = $this->getTitleBottom($module, $props)) {
            $ret[GD_JS_TITLES]['bottom'] = $title_bottom;
        }

        $ret['add-useravatar'] = $this->addUseravatar($module, $props);
    
        $ret['name-htmlmarkup'] = $this->getNameHtmlmarkup($module, $props);
        
        return $ret;
    }
    
    // function initModelProps(array $module, array &$props) {

    //     $this->appendProp($module, $props, 'class', 'visible-loggedin');
    //     parent::initModelProps($module, $props);
    // }
}
