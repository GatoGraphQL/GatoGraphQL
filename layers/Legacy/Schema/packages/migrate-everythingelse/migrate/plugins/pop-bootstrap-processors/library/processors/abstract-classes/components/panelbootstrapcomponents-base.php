<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

abstract class PoP_Module_Processor_PanelBootstrapComponentsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getSubmodules(array $module): array
    {
        return array_merge(
            parent::getSubmodules($module),
            $this->getPanelSubmodules($module)
        );
    }

    public function getPanelSubmodules(array $module)
    {
        return array();
    }

    public function getButtons(array $module)
    {
        return array();
    }
    public function getBodyClass(array $module)
    {
        return array();
    }
    public function getIcon(array $module)
    {
        return array();
    }

    public function getPanelParams(array $module, array &$props)
    {
        return array();
    }

    public function getCustomPanelClass(array $module, array &$props)
    {
        return array();
    }
    public function getPanelClass(array $module)
    {
        return '';
    }
    public function getCustomPanelParams(array $module, array &$props)
    {
        $ret = array();

        // $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        // foreach ($this->getSubmodules($module) as $submodule) {

        //     $submoduleOutputName = ModuleUtils::getModuleOutputName($submodule);
        //     $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($module, $props), $this->getBootstrapcomponentType($module));
        //     $ret[$submoduleOutputName]['data-initjs-targets'] = sprintf(
        //         '%s > %s',
        //         '#'.$frontend_id.'-'.$submoduleOutputName.'-container',
        //         'div.pop-block'
        //     );
        // }

        return $ret;
    }

    public function getPanelactiveClass(array $module)
    {
        return '';
    }

    // protected function getInitjsBlockbranches(array $module, array &$props) {

    //     return array_merge(
    //         parent::getInitjsBlockbranches($module, $props),
    //         $this->getActivemoduleSelectors($module, $props)
    //     );
    // }

    // function getActivemoduleSelectors(array $module, array &$props) {

    //     $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

    //     $ret = array();

    //     foreach ($this->getSubmodules($module) as $submodule) {

    //         $submoduleOutputName = ModuleUtils::getModuleOutputName($submodule);
    //         $frontend_id = PoP_Bootstrap_Utils::getFrontendId(/*$props['block-id']*/$this->getFrontendId($module, $props), $this->getBootstrapcomponentType($module));
    //         $ret[] = sprintf(
    //             '%s > %s > %s',
    //             '#'.$frontend_id.'-'.$submoduleOutputName.'.'.$this->getPanelactiveClass($module),
    //             '#'.$frontend_id.'-'.$submoduleOutputName.'-container',
    //             'div.pop-block'
    //         );
    //     }

    //     return $ret;
    // }

    public function getButtonsClass(array $module, array &$props)
    {
        return array();
    }

    public function isSubmoduleActivePanel(array $module, $submodule)
    {
        return false;
    }
    public function getActivepanelSubmodule(array $module)
    {
        $submodules = $this->getSubmodules($module);
        foreach ($submodules as $submodule) {
            if ($this->isSubmoduleActivePanel($module, $submodule)) {
                return $submodule;
            }
        }

        if ($this->isMandatoryActivePanel($module)) {
            return $this->getDefaultActivepanelSubmodule($module);
        }

        return null;
    }
    protected function isMandatoryActivePanel(array $module)
    {
        return false;
    }

    public function getDefaultActivepanelSubmodule(array $module)
    {

        // Simply return the first one
        $submodules = $this->getSubmodules($module);
        return $submodules[0];
    }

    public function getPanelTitle(array $module)
    {
        return null;
    }
    public function getPanelHeaderType(array $module)
    {
        return null;
    }
    public function getDropdownTitle(array $module)
    {
        return null;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        $ret = array();

        foreach ($this->getSubmodules($module) as $submodule) {
            $ret[] = [
                'header-submodule' => $submodule,
            ];
        }

        return $ret;
    }

    public function getPanelHeaderThumbs(array $module, array &$props)
    {
        return null;
    }
    public function getPanelHeaderTooltips(array $module, array &$props)
    {
        return null;
    }
    public function getPanelHeaderTitles(array $module, array &$props)
    {
        // Comment Leo 19/11/2018: Check this out: initially, this gets the title from the block, but since migrating blocks to dataloads, the processor may not have `getTitle` anymore and the code below explodes
        // $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        // return $moduleprocessor_manager->getProcessor($submodule)->getTitle($submodule, $props);
        return array();
    }
    public function getPanelheaderClass(array $module)
    {
        return '';
    }
    public function getPanelheaderItemClass(array $module)
    {
        return '';
    }
    public function getPanelheaderParams(array $module)
    {
        return array();
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->getPanelHeaderTooltips($module, $props)) {
            $this->addJsmethod($ret, 'tooltip', 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($panel_modules = $this->getPanelSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['panels'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $panel_modules
            );
        }

        // Fill in all the properties
        if ($panel_header_type = $this->getPanelHeaderType($module)) {
            $ret['panel-header-type'] = $panel_header_type;
            $ret['panel-title'] = $this->getPanelTitle($module);

            $titles = $this->getPanelHeaderTitles($module, $props);
            $thumbs = $this->getPanelHeaderThumbs($module, $props);
            $tooltips = $this->getPanelHeaderTooltips($module, $props);

            $panel_headers = array();
            foreach ($this->getPanelHeaders($module, $props) as $panelHeader) {
                $header_submodule = $panelHeader['header-submodule'];
                $subheader_submodules = $panelHeader['subheader-submodules'];
                $headerSubmoduleFullName = ModuleUtils::getModuleFullName($header_submodule);
                $header = array(
                    'moduleoutputname' => ModuleUtils::getModuleOutputName($header_submodule)
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
                        $subheaderSubmoduleFullName = ModuleUtils::getModuleFullName($subheader_submodule);
                        $subheader = array(
                            'moduleoutputname' => ModuleUtils::getModuleOutputName($subheader_submodule)
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

            if ($panelheader_class = $this->getPanelheaderClass($module)) {
                $ret[GD_JS_CLASSES]['panelheader'] = $panelheader_class;
            }
            if ($panelheader_item_class = $this->getPanelheaderItemClass($module)) {
                $ret[GD_JS_CLASSES]['panelheader-item'] = $panelheader_item_class;
            }
            if ($panelheader_params = $this->getPanelheaderParams($module)) {
                $ret['panelheader-params'] = $panelheader_params;
            }
        }

        if ($dropdown_title = $this->getDropdownTitle($module)) {
            $ret[GD_JS_TITLES] = array(
                'dropdown' => $dropdown_title
            );
        }

        if ($buttons_class = $this->getButtonsClass($module, $props)) {
            $ret['buttons-class'] = $buttons_class;
        }
        if ($panel_class = $this->getPanelClass($module)) {
            $ret[GD_JS_CLASSES]['panel'] = $panel_class;
        }
        if ($custom_panel_class = $this->getCustomPanelClass($module, $props)) {
            $ret['custom-panel-class'] = $custom_panel_class;
        }
        if ($panel_params = $this->getPanelParams($module, $props)) {
            $ret['panel-params'] = $panel_params;
        }
        if ($custom_panel_params = $this->getCustomPanelParams($module, $props)) {
            $ret['custom-panel-params'] = $custom_panel_params;
        }
        if ($icon = $this->getIcon($module)) {
            $ret['icon'] = $icon;
        }
        if ($body_class = $this->getBodyClass($module)) {
            $ret['body-class'] = $body_class;
        }
        $ret['buttons'] = $this->getButtons($module);

        return $ret;
    }

    public function getMutableonmodelConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonmodelConfiguration($module, $props);

        if ($active_submodule = $this->getActivepanelSubmodule($module)) {
            $ret['active'] = ModuleUtils::getModuleOutputName($active_submodule);
        }

        return $ret;
    }

    protected function lazyLoadInactivePanels(array $module, array &$props)
    {
        return false;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($this->lazyLoadInactivePanels($module, $props)) {
            $active_submodule = $this->getActivepanelSubmodule($module);
            $inactive_submodules = array_diff(
                $this->getSubmodules($module),
                array(
                    $active_submodule
                )
            );
            foreach ($inactive_submodules as $submodule) {
                $this->setProp([$submodule], $props, 'skip-data-load', true);
            }
        }

        parent::initModelProps($module, $props);
    }

    // function initModelProps(array $module, array &$props) {

    //     $blocktarget = implode(', ', $this->getActivemoduleSelectors($module, $props));
    //     if ($controlgroup_top = $this->getControlgroupTopSubmodule($module)) {
    //         $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
    //     }
    //     if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($module)) {
    //         $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
    //     }

    //     parent::initModelProps($module, $props);
    // }
}
