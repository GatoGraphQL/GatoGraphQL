<?php

abstract class PoP_Module_Processor_HTMLCodesBase extends PoP_Module_Processor_CodesBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_HTMLCODE];
    }

    public function getHtmlTag(array $component, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        if ($html_tag = $this->getHtmlTag($component, $props)) {
            $ret['html-tag'] = $html_tag;
        }
        
        return $ret;
    }
}
