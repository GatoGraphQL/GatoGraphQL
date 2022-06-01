<?php

abstract class PoP_Module_Processor_HTMLCodesBase extends PoP_Module_Processor_CodesBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_HTMLCODE];
    }

    public function getHtmlTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        if ($html_tag = $this->getHtmlTag($component, $props)) {
            $ret['html-tag'] = $html_tag;
        }
        
        return $ret;
    }
}
