<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CONTENT];
    }
    
    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($abovecontent_components = $this->getAbovecontentSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $abovecontent_components
            );
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        return array_merge(
            parent::getLeafComponentFields($component, $props),
            array(
                'content',
            )
        );
    }

    public function getAbovecontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }

    public function getContentMaxlength(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->getContentMaxlength($component, $props)) {
            $this->addJsmethod($ret, 'showmore', 'inner');
        }

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        if ($abovecontent_components = $this->getAbovecontentSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['abovecontent'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $abovecontent_components
            );
        }

        if ($length = $this->getContentMaxlength($component, $props)) {
            $ret['content-maxlength'] = $length;
        }

        return $ret;
    }
}
