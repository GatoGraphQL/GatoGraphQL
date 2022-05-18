<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_PageTabPageSectionsBase extends PoP_Module_Processor_BootstrapPageSectionsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_PAGETAB];
    }

    public function getBtnClass(array $component, array &$props)
    {
        return 'btn btn-default btn-sm';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($component, $props);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-pagesection-page pop-viewport toplevel');
        parent::initModelProps($component, $props);
    }

    protected function getInitjsBlockbranches(array $component, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($component, $props);

        $id = $this->getFrontendId($component, $props);
        $ret[] = '#'.$id.'-merge > div.pop-pagesection-page > div.btn-group > a.pop-pagetab-btn > span.pop-block';

        return $ret;
    }

    protected function openTab(array $component, array &$props)
    {

        // Do not open the tab for 404s
        return !\PoP\Root\App::getState(['routing', 'is-404']);
    }

    public function getPagesectionJsmethod(array $component, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($component, $props);

        $this->addJsmethod($ret, 'activatePageTab', 'activate-interceptor');
        $this->addJsmethod($ret, 'onDestroyPageSwitchTab', 'remove');

        // addOpenTab only if needed. Eg: do not do it in the Embed/Print
        if ($this->openTab($component, $props)) {
            $this->addJsmethod($ret, 'addOpenTab', 'remove');
        }
        $this->addJsmethod($ret, 'closePageTab', 'remove');

        return $ret;
    }
}
