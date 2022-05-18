<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_SubcomponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SUBCOMPONENT];
    }

    public function getSubcomponentField(array $componentVariation)
    {
        return '';
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        return array();
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        return [
            new RelationalModuleField(
                $this->getSubcomponentField($componentVariation),
                $this->getLayoutSubmodules($componentVariation)
            ),
        ];
    }

    public function isIndividual(array $componentVariation, array &$props)
    {
        return true;
    }

    public function getHtmlTag(array $componentVariation, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['subcomponent-field'] = $this->getSubcomponentField($componentVariation);
        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }
        $ret['individual'] = $this->isIndividual($componentVariation, $props);
        $ret['html-tag'] = $this->getHtmlTag($componentVariation, $props);

        return $ret;
    }
}
