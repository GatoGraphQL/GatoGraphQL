<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FullObjectTitleLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLOBJECTTITLE];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array_merge(
            parent::getDataFields($componentVariation, $props),
            array(
                $this->getTitleConditionField($componentVariation, $props),
                $this->getUrlField($componentVariation, $props),
                $this->getTitleField($componentVariation, $props),
                $this->getTitleattrField($componentVariation, $props),
            )
        );
    }

    // function getTitleClass(array $componentVariation, array &$props) {
        
    //     return 'title';
    // }
    
    public function getTitleConditionField(array $componentVariation, array &$props)
    {
        
        // By returning 'id', the condition will always be true by default since all objects have an id >= 1
        return 'id';
    }

    public function getUrlField(array $componentVariation, array &$props)
    {
        return 'url';
    }
    
    public function getTitleField(array $componentVariation, array &$props)
    {
        return '';
    }
    
    public function getTitleattrField(array $componentVariation, array &$props)
    {
        return $this->getTitleField($componentVariation, $props);
    }

    public function getHtmlmarkup(array $componentVariation, array &$props)
    {
        return 'h1';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['title'] = 'title';

        $ret['html-markup'] = $this->getHtmlmarkup($componentVariation, $props);

        $ret['url-field'] = $this->getUrlField($componentVariation, $props);
        $ret['title-condition-field'] = $this->getTitleConditionField($componentVariation, $props);
        $ret['title-field'] = $this->getTitleField($componentVariation, $props);
        if ($titleattr_field = $this->getTitleattrField($componentVariation, $props)) {
            $ret['titleattr-field'] = $titleattr_field;
        }

        return $ret;
    }
}
