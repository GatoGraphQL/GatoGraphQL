<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_EmbedPreviewLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_EMBEDPREVIEW];
    }

    public function getFrameSrc(array $module, array &$props)
    {
        return null;
    }
    public function getFrameSrcField(array $module, array &$props)
    {
        return null;
    }
    public function getFrameWidth(array $module, array &$props)
    {
        return '100%';
    }
    public function getFrameHeight(array $module, array &$props)
    {
        return '400';
    }
    public function printSource(array $module, array &$props)
    {
        return false;
    }
    public function getSourceTitle(array $module, array &$props)
    {
        return sprintf(
            '<em>%s</em>',
            TranslationAPIFacade::getInstance()->__('Source:', 'pop-coreprocessors')
        );
    }
    public function getSourceTarget(array $module, array &$props)
    {
        return '_blank';
    }
    public function getHeader(array $module, array &$props)
    {
        return '';
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);
    
        if ($src_field = $this->getFrameSrcField($module, $props)) {
            $ret[] = $src_field;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['width'] = $this->getFrameWidth($module, $props);
        $ret['height'] = $this->getFrameHeight($module, $props);
        if ($src_field = $this->getFrameSrcField($module, $props)) {
            $ret['src-field'] = $src_field;
        } elseif ($src = $this->getFrameSrc($module, $props)) {
            $ret['src'] = $src;
        }
        if ($this->printSource($module, $props)) {
            $ret['print-source'] = true;
            $ret[GD_JS_TITLES]['source'] = $this->getSourceTitle($module, $props);
            $ret['targets']['source'] = $this->getSourceTarget($module, $props);
        }
        if ($header = $this->getHeader($module, $props)) {
            $ret[GD_JS_TITLES]['header'] = $header;
        }
        
        return $ret;
    }
}
