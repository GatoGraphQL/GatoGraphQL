<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_UserLoggedInsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_UserLogin_TemplateResourceLoaderProcessor::class, PoP_UserLogin_TemplateResourceLoaderProcessor::RESOURCE_USERLOGGEDIN];
    }

    public function addLink(array $componentVariation, array &$props)
    {
        return false;
    }

    public function addUseravatar(array $componentVariation, array &$props)
    {
        return PoP_Application_ConfigurationUtils::useUseravatar();
    }

    public function getTitleTop(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getTitleBottom(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getNameHtmlmarkup(array $componentVariation, array &$props)
    {
        return 'h2';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($this->addLink($componentVariation, $props)) {
            $ret['add-link'] = true;
        }

        if ($title_top = $this->getTitleTop($componentVariation, $props)) {
            $ret[GD_JS_TITLES]['top'] = $title_top;
        }
        if ($title_bottom = $this->getTitleBottom($componentVariation, $props)) {
            $ret[GD_JS_TITLES]['bottom'] = $title_bottom;
        }

        $ret['add-useravatar'] = $this->addUseravatar($componentVariation, $props);
    
        $ret['name-htmlmarkup'] = $this->getNameHtmlmarkup($componentVariation, $props);
        
        return $ret;
    }
    
    // function initModelProps(array $componentVariation, array &$props) {

    //     $this->appendProp($componentVariation, $props, 'class', 'visible-loggedin');
    //     parent::initModelProps($componentVariation, $props);
    // }
}
