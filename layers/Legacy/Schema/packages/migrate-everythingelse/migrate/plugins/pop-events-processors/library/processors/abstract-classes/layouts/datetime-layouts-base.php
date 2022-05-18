<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_DateTimeLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function addDownloadlinks(array $component)
    {
        return false;
    }
    public function getDownloadlinksClass(array $component)
    {
        return 'pull-right';
    }
    public function getSeparator(array $component, array &$props)
    {
        return '<br/>';
    }

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_DATETIME];
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        if ($this->addDownloadlinks($component)) {
            $ret[] = [GD_EM_Module_Processor_QuicklinkButtonGroups::class, GD_EM_Module_Processor_QuicklinkButtonGroups::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN];
        }
        
        return $ret;
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    
        if ($this->addDownloadlinks($component)) {
            $dropdownlinks_component = [GD_EM_Module_Processor_QuicklinkButtonGroups::class, GD_EM_Module_Processor_QuicklinkButtonGroups::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN];
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layout-downloadlinks'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($dropdownlinks_component);

            if ($downloadlinks_class = $this->getDownloadlinksClass($component)) {
                $ret[GD_JS_CLASSES]['downloadlinks'] = $downloadlinks_class;
            }
        }
        $ret[GD_JS_CLASSES]['calendar'] = 'calendar';
        $ret[GD_JS_CLASSES]['date'] = 'date';
        $ret[GD_JS_CLASSES]['time'] = 'time';
        if ($separator = $this->getSeparator($component, $props)) {
            $ret['separator'] = $separator;
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
        return array('dates', 'times');
    }
}
