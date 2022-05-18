<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CONTENT];
    }
    
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($abovecontent_componentVariations = $this->getAbovecontentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $abovecontent_componentVariations
            );
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array_merge(
            parent::getDataFields($componentVariation, $props),
            array(
                'content',
            )
        );
    }

    public function getAbovecontentSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getContentMaxlength(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->getContentMaxlength($componentVariation, $props)) {
            $this->addJsmethod($ret, 'showmore', 'inner');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($abovecontent_componentVariations = $this->getAbovecontentSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $abovecontent_componentVariations
            );
        }

        if ($length = $this->getContentMaxlength($componentVariation, $props)) {
            $ret['content-maxlength'] = $length;
        }

        return $ret;
    }
}
