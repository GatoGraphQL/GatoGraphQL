<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_SpeechBubblesBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::class, PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::RESOURCE_SPEECHBUBBLE];
    }

    public function getLayoutSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $ret[] = $this->getLayoutSubmodule($module);
        
        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        
        $ret[GD_JS_CLASSES] = array(
            'bubble-wrapper' => '',
            'bubble' => 'speechbubble'
        );
        
        $layout = $this->getLayoutSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        
        return $ret;
    }
}
