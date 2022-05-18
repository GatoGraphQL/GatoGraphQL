<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CONTENT];
    }
    
    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($abovecontent_components = $this->getAbovecontentSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $abovecontent_components
            );
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array_merge(
            parent::getDataFields($component, $props),
            array(
                'content',
            )
        );
    }

    public function getAbovecontentSubmodules(array $component)
    {
        return array();
    }

    public function getContentMaxlength(array $component, array &$props)
    {
        return null;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->getContentMaxlength($component, $props)) {
            $this->addJsmethod($ret, 'showmore', 'inner');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        if ($abovecontent_components = $this->getAbovecontentSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $abovecontent_components
            );
        }

        if ($length = $this->getContentMaxlength($component, $props)) {
            $ret['content-maxlength'] = $length;
        }

        return $ret;
    }
}
