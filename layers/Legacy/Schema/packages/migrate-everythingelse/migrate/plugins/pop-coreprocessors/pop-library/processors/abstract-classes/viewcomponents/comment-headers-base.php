<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_CommentViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTPOST];
    }

    public function getHeaderSubmodule(array $componentVariation): ?array
    {
        return null;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        if ($header = $this->getHeaderSubmodule($componentVariation)) {
            return [
                new RelationalModuleField(
                    'customPost',
                    [
                        $header,
                    ]
                ),
            ];
        }

        return parent::getRelationalSubmodules($componentVariation);
    }

    public function headerShowUrl(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($componentVariation, $props)) {
            $ret['header-show-url'] = true;
        }

        if ($header = $this->getHeaderSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['header-post'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($header);
        }

        return $ret;
    }
}
