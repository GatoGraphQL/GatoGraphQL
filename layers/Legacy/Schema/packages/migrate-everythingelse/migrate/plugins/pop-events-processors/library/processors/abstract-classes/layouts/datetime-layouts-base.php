<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_DateTimeLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function addDownloadlinks(array $componentVariation)
    {
        return false;
    }
    public function getDownloadlinksClass(array $componentVariation)
    {
        return 'pull-right';
    }
    public function getSeparator(array $componentVariation, array &$props)
    {
        return '<br/>';
    }

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_DATETIME];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
    
        if ($this->addDownloadlinks($componentVariation)) {
            $ret[] = [GD_EM_Module_Processor_QuicklinkButtonGroups::class, GD_EM_Module_Processor_QuicklinkButtonGroups::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN];
        }
        
        return $ret;
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    
        if ($this->addDownloadlinks($componentVariation)) {
            $dropdownlinks_componentVariation = [GD_EM_Module_Processor_QuicklinkButtonGroups::class, GD_EM_Module_Processor_QuicklinkButtonGroups::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN];
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout-downloadlinks'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($dropdownlinks_componentVariation);

            if ($downloadlinks_class = $this->getDownloadlinksClass($componentVariation)) {
                $ret[GD_JS_CLASSES]['downloadlinks'] = $downloadlinks_class;
            }
        }
        $ret[GD_JS_CLASSES]['calendar'] = 'calendar';
        $ret[GD_JS_CLASSES]['date'] = 'date';
        $ret[GD_JS_CLASSES]['time'] = 'time';
        if ($separator = $this->getSeparator($componentVariation, $props)) {
            $ret['separator'] = $separator;
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
        return array('dates', 'times');
    }
}
