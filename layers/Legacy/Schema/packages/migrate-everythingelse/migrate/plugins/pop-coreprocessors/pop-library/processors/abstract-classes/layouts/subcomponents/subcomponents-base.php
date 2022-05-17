<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_SubcomponentLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SUBCOMPONENT];
    }

    public function getSubcomponentField(array $module)
    {
        return '';
    }

    public function getLayoutSubmodules(array $module)
    {
        return array();
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $module): array
    {
        return [
            new RelationalModuleField(
                $this->getSubcomponentField($module),
                $this->getLayoutSubmodules($module)
            ),
        ];
    }

    public function isIndividual(array $module, array &$props)
    {
        return true;
    }

    public function getHtmlTag(array $module, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret['subcomponent-field'] = $this->getSubcomponentField($module);
        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }
        $ret['individual'] = $this->isIndividual($module, $props);
        $ret['html-tag'] = $this->getHtmlTag($module, $props);

        return $ret;
    }
}
