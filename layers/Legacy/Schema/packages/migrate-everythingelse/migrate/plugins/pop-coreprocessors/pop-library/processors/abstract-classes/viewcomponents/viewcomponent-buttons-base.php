<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ViewComponentButtonsBase extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($header = $this->getHeaderSubcomponent($component)) {
            $ret[] = $header;
        }

        return $ret;
    }

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_BUTTON];
    }

    public function getHeaderSubcomponent(array $component): ?array
    {
        return null;
    }
    public function getUrl(array $component, array &$props)
    {
        return null;
    }

    public function headerShowUrl(array $component)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($url = $this->getUrl($component, $props)) {
            $ret['url'] = $url;
        }

        if ($header = $this->getHeaderSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['header'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($header);
        }

        return $ret;
    }
}
