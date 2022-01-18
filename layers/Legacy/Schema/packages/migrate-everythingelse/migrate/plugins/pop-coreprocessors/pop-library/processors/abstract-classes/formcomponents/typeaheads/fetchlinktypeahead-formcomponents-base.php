<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_FetchlinkTypeaheadFormComponentsBase extends PoP_Module_Processor_TypeaheadFormComponentsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        // Hack: re-use multiple.tmpl
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_MULTIPLE];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'fetchlinkTypeahead');
        return $ret;
    }
    public function getTypeaheadJsmethod(array $module, array &$props)
    {
        return 'fetchlinkTypeahead';
    }

    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        // Add the 'URL' since that's where it will go upon selection of the typeahead value
        $ret[] = 'url';

        return $ret;
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        // Hack: re-use multiple.tmpl
        $input = $this->getInputSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = [
            \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($input),
        ];

        return $ret;
    }
}
