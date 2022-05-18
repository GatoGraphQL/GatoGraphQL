<?php

abstract class PoP_Module_Processor_ReloadEmbedPreviewConnectorsBase extends PoP_Module_Processor_MarkersBase
{
    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'reloadEmbedPreview');
        return $ret;
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {

        // Bind the Embed iframe and the input together. When the input value changes, the iframe
        // will update itself with the URL in the input
        $iframe = $this->getProp($componentVariation, $props, 'iframe-module');
        $this->appendProp($iframe, $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass($iframe));

        parent::initWebPlatformModelProps($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Bind the Embed iframe and the input together. When the input value changes, the iframe
        // will update itself with the URL in the input
        $iframe = $this->getProp($componentVariation, $props, 'iframe-module');
        // $this->setProp($iframe, $props, 'module-cb', true);

        $input = $this->getProp($componentVariation, $props, 'input-module');
        $this->mergeProp(
            $componentVariation,
            $props,
            'previousmodules-ids',
            array(
                'data-iframe-target' => $iframe,
                'data-input-target' => $input,
            )
        );

        parent::initModelProps($componentVariation, $props);
    }
}
