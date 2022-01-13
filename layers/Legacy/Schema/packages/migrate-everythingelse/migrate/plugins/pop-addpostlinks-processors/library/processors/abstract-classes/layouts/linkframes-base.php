<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_AddPostLinks_Module_Processor_LinkFrameLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_AddPostLinksWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AddPostLinksWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LINKFRAME];
    }

    public function getLayoutSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($layout = $this->getLayoutSubmodule($module)) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret[] = 'link';
        $ret[] = 'isLinkEmbeddable';

        return $ret;
    }

    public function showFrameInCollapse(array $module, array &$props)
    {
        return false;
    }

    public function getCollapseClass(array $module, array &$props)
    {
    
        // return 'collapse';
        return '';
    }

    public function getLoadlinkBtnClass(array $module, array &$props)
    {
        return 'btn btn-primary';
    }
    public function getOpennewtabBtnClass(array $module, array &$props)
    {
        return 'btn btn-default';
    }
    public function printSource(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($this->printSource($module, $props)) {
            $ret['print-source'] = true;
            $ret[GD_JS_TITLES]['source'] = TranslationAPIFacade::getInstance()->__('From external link:', 'pop-addpostlinks-processors');
        }
        if ($this->showFrameInCollapse($module, $props)) {
            $ret['show-frame-in-collapse'] = true;
            $ret[GD_JS_CLASSES]['collapse'] = $this->getCollapseClass($module, $props);
            $ret[GD_JS_CLASSES]['loadlink-btn'] = $this->getLoadlinkBtnClass($module, $props);
            $ret[GD_JS_TITLES]['loadlink'] = TranslationAPIFacade::getInstance()->__('Load link', 'pop-addpostlinks-processors');
        }
        $ret[GD_JS_CLASSES]['opennewtab-btn'] = $this->getOpennewtabBtnClass($module, $props);
        $ret[GD_JS_TITLES]['opennewtab'] = TranslationAPIFacade::getInstance()->__('Open link in new tab', 'pop-addpostlinks-processors');

        if ($layout = $this->getLayoutSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = ModuleUtils::getModuleOutputName($layout);
        }
        
        return $ret;
    }

    // function initModelProps(array $module, array &$props) {

    //     if ($this->showFrameInCollapse($module, $props)) {

    //         $this->appendProp($module, $props, 'class', $this->getCollapseClass($module, $props));
    //     }
    //     parent::initModelProps($module, $props);
    // }
}
