<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_PageTabPageSectionsBase extends PoP_Module_Processor_BootstrapPageSectionsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_PAGETAB];
    }

    public function getBtnClass(array $module, array &$props)
    {
        return 'btn btn-default btn-sm';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($module, $props);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'pop-pagesection-page pop-viewport toplevel');
        parent::initModelProps($module, $props);
    }

    protected function getInitjsBlockbranches(array $module, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($module, $props);

        $id = $this->getFrontendId($module, $props);
        $ret[] = '#'.$id.'-merge > div.pop-pagesection-page > div.btn-group > a.pop-pagetab-btn > span.pop-block';

        return $ret;
    }

    protected function openTab(array $module, array &$props)
    {

        // Do not open the tab for 404s
        $vars = ApplicationState::getVars();
        return !\PoP\Root\App::getState(['routing', 'is-404']);
    }

    public function getPagesectionJsmethod(array $module, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($module, $props);

        $this->addJsmethod($ret, 'activatePageTab', 'activate-interceptor');
        $this->addJsmethod($ret, 'onDestroyPageSwitchTab', 'remove');

        // addOpenTab only if needed. Eg: do not do it in the Embed/Print
        if ($this->openTab($module, $props)) {
            $this->addJsmethod($ret, 'addOpenTab', 'remove');
        }
        $this->addJsmethod($ret, 'closePageTab', 'remove');

        return $ret;
    }
}
