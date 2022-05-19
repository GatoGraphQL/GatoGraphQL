<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_SubcomponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SUBCOMPONENT];
    }

    public function getSubcomponentField(array $component)
    {
        return '';
    }

    public function getLayoutSubcomponents(array $component)
    {
        return array();
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubcomponents(array $component): array
    {
        return [
            new RelationalModuleField(
                $this->getSubcomponentField($component),
                $this->getLayoutSubcomponents($component)
            ),
        ];
    }

    public function isIndividual(array $component, array &$props)
    {
        return true;
    }

    public function getHtmlTag(array $component, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['subcomponent-field'] = $this->getSubcomponentField($component);
        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }
        $ret['individual'] = $this->isIndividual($component, $props);
        $ret['html-tag'] = $this->getHtmlTag($component, $props);

        return $ret;
    }
}
