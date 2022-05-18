<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FullObjectTitleLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLOBJECTTITLE];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array_merge(
            parent::getDataFields($component, $props),
            array(
                $this->getTitleConditionField($component, $props),
                $this->getUrlField($component, $props),
                $this->getTitleField($component, $props),
                $this->getTitleattrField($component, $props),
            )
        );
    }

    // function getTitleClass(array $component, array &$props) {
        
    //     return 'title';
    // }
    
    public function getTitleConditionField(array $component, array &$props)
    {
        
        // By returning 'id', the condition will always be true by default since all objects have an id >= 1
        return 'id';
    }

    public function getUrlField(array $component, array &$props)
    {
        return 'url';
    }
    
    public function getTitleField(array $component, array &$props)
    {
        return '';
    }
    
    public function getTitleattrField(array $component, array &$props)
    {
        return $this->getTitleField($component, $props);
    }

    public function getHtmlmarkup(array $component, array &$props)
    {
        return 'h1';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['title'] = 'title';

        $ret['html-markup'] = $this->getHtmlmarkup($component, $props);

        $ret['url-field'] = $this->getUrlField($component, $props);
        $ret['title-condition-field'] = $this->getTitleConditionField($component, $props);
        $ret['title-field'] = $this->getTitleField($component, $props);
        if ($titleattr_field = $this->getTitleattrField($component, $props)) {
            $ret['titleattr-field'] = $titleattr_field;
        }

        return $ret;
    }
}
