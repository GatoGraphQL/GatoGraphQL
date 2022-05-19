<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_UserLoggedInsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_UserLogin_TemplateResourceLoaderProcessor::class, PoP_UserLogin_TemplateResourceLoaderProcessor::RESOURCE_USERLOGGEDIN];
    }

    public function addLink(array $component, array &$props)
    {
        return false;
    }

    public function addUseravatar(array $component, array &$props)
    {
        return PoP_Application_ConfigurationUtils::useUseravatar();
    }

    public function getTitleTop(array $component, array &$props)
    {
        return '';
    }

    public function getTitleBottom(array $component, array &$props)
    {
        return '';
    }

    public function getNameHtmlmarkup(array $component, array &$props)
    {
        return 'h2';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($this->addLink($component, $props)) {
            $ret['add-link'] = true;
        }

        if ($title_top = $this->getTitleTop($component, $props)) {
            $ret[GD_JS_TITLES]['top'] = $title_top;
        }
        if ($title_bottom = $this->getTitleBottom($component, $props)) {
            $ret[GD_JS_TITLES]['bottom'] = $title_bottom;
        }

        $ret['add-useravatar'] = $this->addUseravatar($component, $props);
    
        $ret['name-htmlmarkup'] = $this->getNameHtmlmarkup($component, $props);
        
        return $ret;
    }
    
    // function initModelProps(array $component, array &$props) {

    //     $this->appendProp($component, $props, 'class', 'visible-loggedin');
    //     parent::initModelProps($component, $props);
    // }
}
