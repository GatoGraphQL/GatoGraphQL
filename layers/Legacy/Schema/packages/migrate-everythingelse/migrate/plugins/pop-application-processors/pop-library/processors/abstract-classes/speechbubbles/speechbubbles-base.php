<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_SpeechBubblesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::class, PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::RESOURCE_SPEECHBUBBLE];
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $ret[] = $this->getLayoutSubmodule($componentVariation);
        
        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
    
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        
        $ret[GD_JS_CLASSES] = array(
            'bubble-wrapper' => '',
            'bubble' => 'speechbubble'
        );
        
        $layout = $this->getLayoutSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        
        return $ret;
    }
}
