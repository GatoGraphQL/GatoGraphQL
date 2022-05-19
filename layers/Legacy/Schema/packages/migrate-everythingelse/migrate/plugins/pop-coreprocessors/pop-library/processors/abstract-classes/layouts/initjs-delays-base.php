<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_InitJSDelayLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_INITJSDELAY];
    }

    public function getLayoutSubcomponent(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($layout = $this->getLayoutSubcomponent($component)) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($layout = $this->getLayoutSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($layout);
        }
        
        return $ret;
    }
}
