<?php

abstract class PoP_Module_Processor_ReloadEmbedPreviewConnectorsBase extends PoP_Module_Processor_MarkersBase
{
    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'reloadEmbedPreview');
        return $ret;
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {

        // Bind the Embed iframe and the input together. When the input value changes, the iframe
        // will update itself with the URL in the input
        $iframe = $this->getProp($component, $props, 'iframe-module');
        $this->appendProp($iframe, $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass($iframe));

        parent::initWebPlatformModelProps($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Bind the Embed iframe and the input together. When the input value changes, the iframe
        // will update itself with the URL in the input
        $iframe = $this->getProp($component, $props, 'iframe-module');
        // $this->setProp($iframe, $props, 'module-cb', true);

        $input = $this->getProp($component, $props, 'input-module');
        $this->mergeProp(
            $component,
            $props,
            'previousmodules-ids',
            array(
                'data-iframe-target' => $iframe,
                'data-input-target' => $input,
            )
        );

        parent::initModelProps($component, $props);
    }
}
