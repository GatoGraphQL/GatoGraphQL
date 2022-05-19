<?php

abstract class PoP_Module_Processor_ModalPageSectionsBase extends PoP_Module_Processor_BootstrapPageSectionsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_MODAL];
    }

    public function getHeaderClass(array $component)
    {
        return '';
    }
    public function getDialogClasses(array $component)
    {
        return array();
    }
    public function getBodyClasses(array $component)
    {
        $ret = array();

        foreach ($this->getSubcomponents($component) as $subComponent) {
            $subcomponentOutputName = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($subComponent);
            $ret[$subcomponentOutputName] = 'modal-body';
        }

        return $ret;
    }
    public function getHeaderTitles(array $component)
    {
        return array();
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($header_class = $this->getHeaderClass($component)) {
            $ret[GD_JS_CLASSES]['header'] = $header_class;
        }
        // Only send the classes/titles that are targetted to any of the subcomponents
        $subComponents = array_flip($this->getInnerSubcomponents($component));
        if ($dialogs_class = array_intersect_key($this->getDialogClasses($component), $subComponents)) {
            $ret[GD_JS_CLASSES]['dialogs'] = $dialogs_class;
        }
        if ($bodies_class = array_intersect_key($this->getBodyClasses($component), $subComponents)) {
            $ret[GD_JS_CLASSES]['bodies'] = $bodies_class;
        }
        if ($headerTitles = array_intersect_key($this->getHeaderTitles($component), $subComponents)) {
            $ret[GD_JS_TITLES]['headers'] = $headerTitles;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-pagesection-page pop-viewport toplevel');
        $this->mergeProp(
            $component,
            $props,
            'params',
            array(
                'data-paramsscope' => GD_SETTINGS_PARAMSSCOPE_URL
            )
        );
        parent::initModelProps($component, $props);
    }

    protected function getInitjsBlockbranches(array $component, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($component, $props);

        // 2 possibilities: with the merge container (eg: main) or without it (eg: quickview)
        $id = $this->getFrontendId($component, $props);
        $ret[] = '#'.$id.' > .modal.in > .pop-modaldialog > .pop-modalcontent > .pop-modalbody > .pop-block';

        return $ret;
    }
}
