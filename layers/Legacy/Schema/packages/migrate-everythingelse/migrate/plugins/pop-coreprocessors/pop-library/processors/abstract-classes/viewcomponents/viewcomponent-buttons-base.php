<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ViewComponentButtonsBase extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($header = $this->getHeaderSubmodule($componentVariation)) {
            $ret[] = $header;
        }

        return $ret;
    }

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_BUTTON];
    }

    public function getHeaderSubmodule(array $componentVariation): ?array
    {
        return null;
    }
    public function getUrl(array $componentVariation, array &$props)
    {
        return null;
    }

    public function headerShowUrl(array $componentVariation)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($url = $this->getUrl($componentVariation, $props)) {
            $ret['url'] = $url;
        }

        if ($header = $this->getHeaderSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['header'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($header);
        }

        return $ret;
    }
}
