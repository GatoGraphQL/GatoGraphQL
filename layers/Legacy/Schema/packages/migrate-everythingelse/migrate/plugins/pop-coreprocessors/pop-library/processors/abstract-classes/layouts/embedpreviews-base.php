<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_EmbedPreviewLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_EMBEDPREVIEW];
    }

    public function getFrameSrc(array $component, array &$props)
    {
        return null;
    }
    public function getFrameSrcField(array $component, array &$props)
    {
        return null;
    }
    public function getFrameWidth(array $component, array &$props)
    {
        return '100%';
    }
    public function getFrameHeight(array $component, array &$props)
    {
        return '400';
    }
    public function printSource(array $component, array &$props)
    {
        return false;
    }
    public function getSourceTitle(array $component, array &$props)
    {
        return sprintf(
            '<em>%s</em>',
            TranslationAPIFacade::getInstance()->__('Source:', 'pop-coreprocessors')
        );
    }
    public function getSourceTarget(array $component, array &$props)
    {
        return '_blank';
    }
    public function getHeader(array $component, array &$props)
    {
        return '';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);
    
        if ($src_field = $this->getFrameSrcField($component, $props)) {
            $ret[] = $src_field;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['width'] = $this->getFrameWidth($component, $props);
        $ret['height'] = $this->getFrameHeight($component, $props);
        if ($src_field = $this->getFrameSrcField($component, $props)) {
            $ret['src-field'] = $src_field;
        } elseif ($src = $this->getFrameSrc($component, $props)) {
            $ret['src'] = $src;
        }
        if ($this->printSource($component, $props)) {
            $ret['print-source'] = true;
            $ret[GD_JS_TITLES]['source'] = $this->getSourceTitle($component, $props);
            $ret['targets']['source'] = $this->getSourceTarget($component, $props);
        }
        if ($header = $this->getHeader($component, $props)) {
            $ret[GD_JS_TITLES]['header'] = $header;
        }
        
        return $ret;
    }
}
