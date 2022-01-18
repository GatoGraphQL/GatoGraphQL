<?php

abstract class PoP_Module_Processor_ModalPageSectionsBase extends PoP_Module_Processor_BootstrapPageSectionsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_MODAL];
    }

    public function getHeaderClass(array $module)
    {
        return '';
    }
    public function getDialogClasses(array $module)
    {
        return array();
    }
    public function getBodyClasses(array $module)
    {
        $ret = array();

        foreach ($this->getSubmodules($module) as $submodule) {
            $submoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($submodule);
            $ret[$submoduleOutputName] = 'modal-body';
        }

        return $ret;
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
        // Only send the classes/titles that are targetted to any of the submodules
        $submodules = array_flip($this->getInnerSubmodules($module));
        if ($dialogs_class = array_intersect_key($this->getDialogClasses($module), $submodules)) {
            $ret[GD_JS_CLASSES]['dialogs'] = $dialogs_class;
        }
        if ($bodies_class = array_intersect_key($this->getBodyClasses($module), $submodules)) {
            $ret[GD_JS_CLASSES]['bodies'] = $bodies_class;
        }
        if ($headerTitles = array_intersect_key($this->getHeaderTitles($module), $submodules)) {
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
        parent::initModelProps($module, $props);
    }

    protected function getInitjsBlockbranches(array $module, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($module, $props);

        // 2 possibilities: with the merge container (eg: main) or without it (eg: quickview)
        $id = $this->getFrontendId($module, $props);
        $ret[] = '#'.$id.' > .modal.in > .pop-modaldialog > .pop-modalcontent > .pop-modalbody > .pop-block';

        return $ret;
    }
}
