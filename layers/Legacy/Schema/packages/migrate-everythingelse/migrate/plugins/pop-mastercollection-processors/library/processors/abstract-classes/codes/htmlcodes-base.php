<?php

abstract class PoP_Module_Processor_HTMLCodesBase extends PoP_Module_Processor_CodesBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_HTMLCODE];
    }

    public function getHtmlTag(array $componentVariation, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
    
        if ($html_tag = $this->getHtmlTag($componentVariation, $props)) {
            $ret['html-tag'] = $html_tag;
        }
        
        return $ret;
    }
}
