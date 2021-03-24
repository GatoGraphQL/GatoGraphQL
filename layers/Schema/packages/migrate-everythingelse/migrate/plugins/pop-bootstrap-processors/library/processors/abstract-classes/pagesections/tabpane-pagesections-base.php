<?php

use PoP\ComponentModel\Misc\RequestUtils;

abstract class PoP_Module_Processor_TabPanePageSectionsBase extends PoP_Module_Processor_BootstrapPageSectionsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_TABPANE];
    }

    public function getHeaderClass(array $module)
    {
        return '';
    }
    public function getHeaderTitles(array $module)
    {
        return array();
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($header_class = $this->getHeaderClass($module)) {
            $ret[GD_JS_CLASSES]['header'] = $header_class;
        }
        if ($headerTitles = $this->getHeaderTitles($module)) {
            $ret[GD_JS_TITLES]['headers'] = $headerTitles;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'pop-pagesection-page pop-viewport toplevel');
        $this->mergeProp(
            $module,
            $props,
            'params',
            array(
                'data-paramsscope' => GD_SETTINGS_PARAMSSCOPE_URL
            )
        );

        // If PoP SSR is not defined, then there is no PoP_SSR_ServerUtils
        if (defined('POP_SSR_INITIALIZED')) {
            // If doing Server-side rendering, then already add "active" to the tabPane, to not depend on javascript
            // (Otherwise, the page will look empty!)
            if (RequestUtils::loadingSite() && !PoP_SSR_ServerUtils::disableServerSideRendering()) {
                $this->appendProp($module, $props, 'class', 'active');
            }
        }

        parent::initModelProps($module, $props);
    }

    protected function getInitjsBlockbranches(array $module, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($module, $props);

        // 2 possibilities: with the merge container (eg: main) or without it (eg: quickview)
        $id = $this->getFrontendId($module, $props);

        // Comment Leo 10/12/2016: in the past, we did .tab-pane.active, however that doesn't work anymore for when alt+click to open a link
        // So instead, just pick the last added .tab-pane
        $ret[] = '#'.$id.'-merge > div.tab-pane:last-child > div.pop-block';
        $ret[] = '#'.$id.' > div.tab-pane:last-child > div.pop-block';

        return $ret;
    }
}
