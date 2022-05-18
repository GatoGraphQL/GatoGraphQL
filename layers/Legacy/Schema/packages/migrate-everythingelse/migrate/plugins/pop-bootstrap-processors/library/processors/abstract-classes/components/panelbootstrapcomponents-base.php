<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PanelBootstrapComponentsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getSubComponentVariations(array $componentVariation): array
    {
        return array_merge(
            parent::getSubComponentVariations($componentVariation),
            $this->getPanelSubmodules($componentVariation)
        );
    }

    public function getPanelSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getButtons(array $componentVariation)
    {
        return array();
    }
    public function getBodyClass(array $componentVariation)
    {
        return array();
    }
    public function getIcon(array $componentVariation)
    {
        return array();
    }

    public function getPanelParams(array $componentVariation, array &$props)
    {
        return array();
    }

    public function getCustomPanelClass(array $componentVariation, array &$props)
    {
        return array();
    }
    public function getPanelClass(array $componentVariation)
    {
        return '';
    }
    public function getCustomPanelParams(array $componentVariation, array &$props)
    {
        $ret = array();

        // $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        // foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {

        //     $submoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subComponentVariation);
        //     $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($componentVariation, $props), $this->getBootstrapcomponentType($componentVariation));
        //     $ret[$submoduleOutputName]['data-initjs-targets'] = sprintf(
        //         '%s > %s',
        //         '#'.$frontend_id.'-'.$submoduleOutputName.'-container',
        //         'div.pop-block'
        //     );
        // }

        return $ret;
    }

    public function getPanelactiveClass(array $componentVariation)
    {
        return '';
    }

    // protected function getInitjsBlockbranches(array $componentVariation, array &$props) {

    //     return array_merge(
    //         parent::getInitjsBlockbranches($componentVariation, $props),
    //         $this->getActivemoduleSelectors($componentVariation, $props)
    //     );
    // }

    // function getActivemoduleSelectors(array $componentVariation, array &$props) {

    //     $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    //     $ret = array();

    //     foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {

    //         $submoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subComponentVariation);
    //         $frontend_id = PoP_Bootstrap_Utils::getFrontendId(/*$props['block-id']*/$this->getFrontendId($componentVariation, $props), $this->getBootstrapcomponentType($componentVariation));
    //         $ret[] = sprintf(
    //             '%s > %s > %s',
    //             '#'.$frontend_id.'-'.$submoduleOutputName.'.'.$this->getPanelactiveClass($componentVariation),
    //             '#'.$frontend_id.'-'.$submoduleOutputName.'-container',
    //             'div.pop-block'
    //         );
    //     }

    //     return $ret;
    // }

    public function getButtonsClass(array $componentVariation, array &$props)
    {
        return array();
    }

    public function isSubmoduleActivePanel(array $componentVariation, $subComponentVariation)
    {
        return false;
    }
    public function getActivepanelSubmodule(array $componentVariation)
    {
        $subComponentVariations = $this->getSubComponentVariations($componentVariation);
        foreach ($subComponentVariations as $subComponentVariation) {
            if ($this->isSubmoduleActivePanel($componentVariation, $subComponentVariation)) {
                return $subComponentVariation;
            }
        }

        if ($this->isMandatoryActivePanel($componentVariation)) {
            return $this->getDefaultActivepanelSubmodule($componentVariation);
        }

        return null;
    }
    protected function isMandatoryActivePanel(array $componentVariation)
    {
        return false;
    }

    public function getDefaultActivepanelSubmodule(array $componentVariation)
    {

        // Simply return the first one
        $subComponentVariations = $this->getSubComponentVariations($componentVariation);
        return $subComponentVariations[0];
    }

    public function getPanelTitle(array $componentVariation)
    {
        return null;
    }
    public function getPanelHeaderType(array $componentVariation)
    {
        return null;
    }
    public function getDropdownTitle(array $componentVariation)
    {
        return null;
    }

    public function getPanelHeaders(array $componentVariation, array &$props)
    {
        $ret = array();

        foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
            $ret[] = [
                'header-submodule' => $subComponentVariation,
            ];
        }

        return $ret;
    }

    public function getPanelHeaderThumbs(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getPanelHeaderTooltips(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getPanelHeaderTitles(array $componentVariation, array &$props)
    {
        // Comment Leo 19/11/2018: Check this out: initially, this gets the title from the block, but since migrating blocks to dataloads, the processor may not have `getTitle` anymore and the code below explodes
        // $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        // return $componentprocessor_manager->getProcessor($subComponentVariation)->getTitle($subComponentVariation, $props);
        return array();
    }
    public function getPanelheaderClass(array $componentVariation)
    {
        return '';
    }
    public function getPanelheaderItemClass(array $componentVariation)
    {
        return '';
    }
    public function getPanelheaderParams(array $componentVariation)
    {
        return array();
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->getPanelHeaderTooltips($componentVariation, $props)) {
            $this->addJsmethod($ret, 'tooltip', 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($panel_componentVariations = $this->getPanelSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['panels'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $panel_componentVariations
            );
        }

        // Fill in all the properties
        if ($panel_header_type = $this->getPanelHeaderType($componentVariation)) {
            $ret['panel-header-type'] = $panel_header_type;
            $ret['panel-title'] = $this->getPanelTitle($componentVariation);

            $titles = $this->getPanelHeaderTitles($componentVariation, $props);
            $thumbs = $this->getPanelHeaderThumbs($componentVariation, $props);
            $tooltips = $this->getPanelHeaderTooltips($componentVariation, $props);

            $panel_headers = array();
            foreach ($this->getPanelHeaders($componentVariation, $props) as $panelHeader) {
                $header_submodule = $panelHeader['header-submodule'];
                $subheader_submodules = $panelHeader['subheader-submodules'];
                $headerSubmoduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($header_submodule);
                $header = array(
                    'moduleoutputname' => \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($header_submodule)
                );
                if ($title = $titles[$headerSubmoduleFullName] ?? null) {
                    $header['title'] = $title;
                }
                if ($thumb = $thumbs[$headerSubmoduleFullName] ?? null) {
                    $header['fontawesome'] = $thumb;
                }
                if ($tooltip = $tooltips[$headerSubmoduleFullName] ?? null) {
                    $header['tooltip'] = $tooltip;
                }

                if ($subheader_submodules) {
                    $subheaders = array();
                    foreach ($subheader_submodules as $subheader_submodule) {
                        $subheaderSubmoduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($subheader_submodule);
                        $subheader = array(
                            'moduleoutputname' => \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subheader_submodule)
                        );
                        if ($title = $titles[$subheaderSubmoduleFullName] ?? null) {
                            $subheader['title'] = $title;
                        }
                        if ($thumb = $thumbs[$subheaderSubmoduleFullName] ?? null) {
                            $subheader['fontawesome'] = $thumb;
                        }
                        if ($tooltip = $tooltips[$subheaderSubmoduleFullName] ?? null) {
                            $subheader['tooltip'] = $tooltip;
                        }
                        $subheaders[] = $subheader;
                    }

                    $header['subheaders'] = $subheaders;
                }

                $panel_headers[] = $header;
            }
            $ret['panel-headers'] = $panel_headers;

            if ($panelheader_class = $this->getPanelheaderClass($componentVariation)) {
                $ret[GD_JS_CLASSES]['panelheader'] = $panelheader_class;
            }
            if ($panelheader_item_class = $this->getPanelheaderItemClass($componentVariation)) {
                $ret[GD_JS_CLASSES]['panelheader-item'] = $panelheader_item_class;
            }
            if ($panelheader_params = $this->getPanelheaderParams($componentVariation)) {
                $ret['panelheader-params'] = $panelheader_params;
            }
        }

        if ($dropdown_title = $this->getDropdownTitle($componentVariation)) {
            $ret[GD_JS_TITLES] = array(
                'dropdown' => $dropdown_title
            );
        }

        if ($buttons_class = $this->getButtonsClass($componentVariation, $props)) {
            $ret['buttons-class'] = $buttons_class;
        }
        if ($panel_class = $this->getPanelClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['panel'] = $panel_class;
        }
        if ($custom_panel_class = $this->getCustomPanelClass($componentVariation, $props)) {
            $ret['custom-panel-class'] = $custom_panel_class;
        }
        if ($panel_params = $this->getPanelParams($componentVariation, $props)) {
            $ret['panel-params'] = $panel_params;
        }
        if ($custom_panel_params = $this->getCustomPanelParams($componentVariation, $props)) {
            $ret['custom-panel-params'] = $custom_panel_params;
        }
        if ($icon = $this->getIcon($componentVariation)) {
            $ret['icon'] = $icon;
        }
        if ($body_class = $this->getBodyClass($componentVariation)) {
            $ret['body-class'] = $body_class;
        }
        $ret['buttons'] = $this->getButtons($componentVariation);

        return $ret;
    }

    public function getMutableonmodelConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonmodelConfiguration($componentVariation, $props);

        if ($active_submodule = $this->getActivepanelSubmodule($componentVariation)) {
            $ret['active'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($active_submodule);
        }

        return $ret;
    }

    protected function lazyLoadInactivePanels(array $componentVariation, array &$props)
    {
        return false;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($this->lazyLoadInactivePanels($componentVariation, $props)) {
            $active_submodule = $this->getActivepanelSubmodule($componentVariation);
            $inactive_submodules = array_diff(
                $this->getSubComponentVariations($componentVariation),
                array(
                    $active_submodule
                )
            );
            foreach ($inactive_submodules as $subComponentVariation) {
                $this->setProp([$subComponentVariation], $props, 'skip-data-load', true);
            }
        }

        parent::initModelProps($componentVariation, $props);
    }

    // function initModelProps(array $componentVariation, array &$props) {

    //     $blocktarget = implode(', ', $this->getActivemoduleSelectors($componentVariation, $props));
    //     if ($controlgroup_top = $this->getControlgroupTopSubmodule($componentVariation)) {
    //         $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
    //     }
    //     if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($componentVariation)) {
    //         $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
    //     }

    //     parent::initModelProps($componentVariation, $props);
    // }
}
