<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_PageTabPageSectionsBase extends PoP_Module_Processor_BootstrapPageSectionsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_PAGETAB];
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        return 'btn btn-default btn-sm';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($componentVariation, $props);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'pop-pagesection-page pop-viewport toplevel');
        parent::initModelProps($componentVariation, $props);
    }

    protected function getInitjsBlockbranches(array $componentVariation, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($componentVariation, $props);

        $id = $this->getFrontendId($componentVariation, $props);
        $ret[] = '#'.$id.'-merge > div.pop-pagesection-page > div.btn-group > a.pop-pagetab-btn > span.pop-block';

        return $ret;
    }

    protected function openTab(array $componentVariation, array &$props)
    {

        // Do not open the tab for 404s
        return !\PoP\Root\App::getState(['routing', 'is-404']);
    }

    public function getPagesectionJsmethod(array $componentVariation, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($componentVariation, $props);

        $this->addJsmethod($ret, 'activatePageTab', 'activate-interceptor');
        $this->addJsmethod($ret, 'onDestroyPageSwitchTab', 'remove');

        // addOpenTab only if needed. Eg: do not do it in the Embed/Print
        if ($this->openTab($componentVariation, $props)) {
            $this->addJsmethod($ret, 'addOpenTab', 'remove');
        }
        $this->addJsmethod($ret, 'closePageTab', 'remove');

        return $ret;
    }
}
