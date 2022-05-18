<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FullObjectTitleLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLOBJECTTITLE];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array_merge(
            parent::getDataFields($module, $props),
            array(
                $this->getTitleConditionField($module, $props),
                $this->getUrlField($module, $props),
                $this->getTitleField($module, $props),
                $this->getTitleattrField($module, $props),
            )
        );
    }

    // function getTitleClass(array $module, array &$props) {
        
    //     return 'title';
    // }
    
    public function getTitleConditionField(array $module, array &$props)
    {
        
        // By returning 'id', the condition will always be true by default since all objects have an id >= 1
        return 'id';
    }

    public function getUrlField(array $module, array &$props)
    {
        return 'url';
    }
    
    public function getTitleField(array $module, array &$props)
    {
        return '';
    }
    
    public function getTitleattrField(array $module, array &$props)
    {
        return $this->getTitleField($module, $props);
    }

    public function getHtmlmarkup(array $module, array &$props)
    {
        return 'h1';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['title'] = 'title';

        $ret['html-markup'] = $this->getHtmlmarkup($module, $props);

        $ret['url-field'] = $this->getUrlField($module, $props);
        $ret['title-condition-field'] = $this->getTitleConditionField($module, $props);
        $ret['title-field'] = $this->getTitleField($module, $props);
        if ($titleattr_field = $this->getTitleattrField($module, $props)) {
            $ret['titleattr-field'] = $titleattr_field;
        }

        return $ret;
    }
}
