<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\RelationalModuleField;

abstract class PoP_Module_Processor_CommentViewComponentHeadersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTPOST];
    }

    public function getHeaderSubmodule(array $module): ?array
    {
        return null;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        if ($header = $this->getHeaderSubmodule($module)) {
            return [
                new RelationalModuleField(
                    'customPost',
                    [
                        $header,
                    ]
                ),
            ];
        }

        return parent::getDomainSwitchingSubmodules($module);
    }

    public function headerShowUrl(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($module, $props)) {
            $ret['header-show-url'] = true;
        }

        if ($header = $this->getHeaderSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['header-post'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($header);
        }

        return $ret;
    }
}
