<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_SpeechBubblesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::class, PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::RESOURCE_SPEECHBUBBLE];
    }

    public function getLayoutSubmodule(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $ret[] = $this->getLayoutSubmodule($component);
        
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
        
        $layout = $this->getLayoutSubmodule($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        
        return $ret;
    }
}
