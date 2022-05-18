<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_EmbedPreviewLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_EMBEDPREVIEW];
    }

    public function getFrameSrc(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getFrameSrcField(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getFrameWidth(array $componentVariation, array &$props)
    {
        return '100%';
    }
    public function getFrameHeight(array $componentVariation, array &$props)
    {
        return '400';
    }
    public function printSource(array $componentVariation, array &$props)
    {
        return false;
    }
    public function getSourceTitle(array $componentVariation, array &$props)
    {
        return sprintf(
            '<em>%s</em>',
            TranslationAPIFacade::getInstance()->__('Source:', 'pop-coreprocessors')
        );
    }
    public function getSourceTarget(array $componentVariation, array &$props)
    {
        return '_blank';
    }
    public function getHeader(array $componentVariation, array &$props)
    {
        return '';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);
    
        if ($src_field = $this->getFrameSrcField($componentVariation, $props)) {
            $ret[] = $src_field;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['width'] = $this->getFrameWidth($componentVariation, $props);
        $ret['height'] = $this->getFrameHeight($componentVariation, $props);
        if ($src_field = $this->getFrameSrcField($componentVariation, $props)) {
            $ret['src-field'] = $src_field;
        } elseif ($src = $this->getFrameSrc($componentVariation, $props)) {
            $ret['src'] = $src;
        }
        if ($this->printSource($componentVariation, $props)) {
            $ret['print-source'] = true;
            $ret[GD_JS_TITLES]['source'] = $this->getSourceTitle($componentVariation, $props);
            $ret['targets']['source'] = $this->getSourceTarget($componentVariation, $props);
        }
        if ($header = $this->getHeader($componentVariation, $props)) {
            $ret[GD_JS_TITLES]['header'] = $header;
        }
        
        return $ret;
    }
}
