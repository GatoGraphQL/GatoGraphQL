<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_SpeechBubblesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::class, PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::RESOURCE_SPEECHBUBBLE];
    }

    public function getLayoutSubcomponent(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $ret[] = $this->getLayoutSubcomponent($component);
        
        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        
        $ret[GD_JS_CLASSES] = array(
            'bubble-wrapper' => '',
            'bubble' => 'speechbubble'
        );
        
        $layout = $this->getLayoutSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($layout);
        
        return $ret;
    }
}
