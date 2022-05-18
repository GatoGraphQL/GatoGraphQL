<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_AddPostLinks_Module_Processor_LinkFrameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_AddPostLinksWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AddPostLinksWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LINKFRAME];
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($layout = $this->getLayoutSubmodule($componentVariation)) {
            $ret[] = $layout;
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
        $ret = parent::getDataFields($componentVariation, $props);

        $ret[] = 'link';
        $ret[] = 'isLinkEmbeddable';

        return $ret;
    }

    public function showFrameInCollapse(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getCollapseClass(array $componentVariation, array &$props)
    {
    
        // return 'collapse';
        return '';
    }

    public function getLoadlinkBtnClass(array $componentVariation, array &$props)
    {
        return 'btn btn-primary';
    }
    public function getOpennewtabBtnClass(array $componentVariation, array &$props)
    {
        return 'btn btn-default';
    }
    public function printSource(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($this->printSource($componentVariation, $props)) {
            $ret['print-source'] = true;
            $ret[GD_JS_TITLES]['source'] = TranslationAPIFacade::getInstance()->__('From external link:', 'pop-addpostlinks-processors');
        }
        if ($this->showFrameInCollapse($componentVariation, $props)) {
            $ret['show-frame-in-collapse'] = true;
            $ret[GD_JS_CLASSES]['collapse'] = $this->getCollapseClass($componentVariation, $props);
            $ret[GD_JS_CLASSES]['loadlink-btn'] = $this->getLoadlinkBtnClass($componentVariation, $props);
            $ret[GD_JS_TITLES]['loadlink'] = TranslationAPIFacade::getInstance()->__('Load link', 'pop-addpostlinks-processors');
        }
        $ret[GD_JS_CLASSES]['opennewtab-btn'] = $this->getOpennewtabBtnClass($componentVariation, $props);
        $ret[GD_JS_TITLES]['opennewtab'] = TranslationAPIFacade::getInstance()->__('Open link in new tab', 'pop-addpostlinks-processors');

        if ($layout = $this->getLayoutSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        }
        
        return $ret;
    }

    // function initModelProps(array $componentVariation, array &$props) {

    //     if ($this->showFrameInCollapse($componentVariation, $props)) {

    //         $this->appendProp($componentVariation, $props, 'class', $this->getCollapseClass($componentVariation, $props));
    //     }
    //     parent::initModelProps($componentVariation, $props);
    // }
}
