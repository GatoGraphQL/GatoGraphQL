<?php

abstract class PoP_Module_Processor_ModalPageSectionsBase extends PoP_Module_Processor_BootstrapPageSectionsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_PAGESECTION_MODAL];
    }

    public function getHeaderClass(array $componentVariation)
    {
        return '';
    }
    public function getDialogClasses(array $componentVariation)
    {
        return array();
    }
    public function getBodyClasses(array $componentVariation)
    {
        $ret = array();

        foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
            $submoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subComponentVariation);
            $ret[$submoduleOutputName] = 'modal-body';
        }

        return $ret;
    }
    public function getHeaderTitles(array $componentVariation)
    {
        return array();
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($header_class = $this->getHeaderClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['header'] = $header_class;
        }
        // Only send the classes/titles that are targetted to any of the submodules
        $subComponentVariations = array_flip($this->getInnerSubmodules($componentVariation));
        if ($dialogs_class = array_intersect_key($this->getDialogClasses($componentVariation), $subComponentVariations)) {
            $ret[GD_JS_CLASSES]['dialogs'] = $dialogs_class;
        }
        if ($bodies_class = array_intersect_key($this->getBodyClasses($componentVariation), $subComponentVariations)) {
            $ret[GD_JS_CLASSES]['bodies'] = $bodies_class;
        }
        if ($headerTitles = array_intersect_key($this->getHeaderTitles($componentVariation), $subComponentVariations)) {
            $ret[GD_JS_TITLES]['headers'] = $headerTitles;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'pop-pagesection-page pop-viewport toplevel');
        $this->mergeProp(
            $componentVariation,
            $props,
            'params',
            array(
                'data-paramsscope' => GD_SETTINGS_PARAMSSCOPE_URL
            )
        );
        parent::initModelProps($componentVariation, $props);
    }

    protected function getInitjsBlockbranches(array $componentVariation, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($componentVariation, $props);

        // 2 possibilities: with the merge container (eg: main) or without it (eg: quickview)
        $id = $this->getFrontendId($componentVariation, $props);
        $ret[] = '#'.$id.' > .modal.in > .pop-modaldialog > .pop-modalcontent > .pop-modalbody > .pop-block';

        return $ret;
    }
}
