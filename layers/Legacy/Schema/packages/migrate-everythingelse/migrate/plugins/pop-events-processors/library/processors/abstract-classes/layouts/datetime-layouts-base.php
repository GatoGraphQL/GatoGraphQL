<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class GD_EM_Module_Processor_DateTimeLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function addDownloadlinks(array $module)
    {
        return false;
    }
    public function getDownloadlinksClass(array $module)
    {
        return 'pull-right';
    }
    public function getSeparator(array $module, array &$props)
    {
        return '<br/>';
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_DATETIME];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        if ($this->addDownloadlinks($module)) {
            $ret[] = [GD_EM_Module_Processor_QuicklinkButtonGroups::class, GD_EM_Module_Processor_QuicklinkButtonGroups::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN];
        }
        
        return $ret;
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
    
        if ($this->addDownloadlinks($module)) {
            $dropdownlinks_module = [GD_EM_Module_Processor_QuicklinkButtonGroups::class, GD_EM_Module_Processor_QuicklinkButtonGroups::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN];
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout-downloadlinks'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($dropdownlinks_module);

            if ($downloadlinks_class = $this->getDownloadlinksClass($module)) {
                $ret[GD_JS_CLASSES]['downloadlinks'] = $downloadlinks_class;
            }
        }
        $ret[GD_JS_CLASSES]['calendar'] = 'calendar';
        $ret[GD_JS_CLASSES]['date'] = 'date';
        $ret[GD_JS_CLASSES]['time'] = 'time';
        if ($separator = $this->getSeparator($module, $props)) {
            $ret['separator'] = $separator;
        }
        
        return $ret;
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('dates', 'times');
    }
}
