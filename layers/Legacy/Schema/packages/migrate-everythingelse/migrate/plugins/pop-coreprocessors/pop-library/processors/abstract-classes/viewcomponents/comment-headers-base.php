<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_CommentViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTPOST];
    }

    public function getHeaderSubmodule(array $component): ?array
    {
        return null;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        if ($header = $this->getHeaderSubmodule($component)) {
            return [
                new RelationalModuleField(
                    'customPost',
                    [
                        $header,
                    ]
                ),
            ];
        }

        return parent::getRelationalSubmodules($component);
    }

    public function headerShowUrl(array $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($component, $props)) {
            $ret['header-show-url'] = true;
        }

        if ($header = $this->getHeaderSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['header-post'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($header);
        }

        return $ret;
    }
}
