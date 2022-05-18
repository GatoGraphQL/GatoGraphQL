<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PanelBootstrapComponentsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getPanelSubmodules($component)
        );
    }

    public function getPanelSubmodules(array $component)
    {
        return array();
    }

    public function getButtons(array $component)
    {
        return array();
    }
    public function getBodyClass(array $component)
    {
        return array();
    }
    public function getIcon(array $component)
    {
        return array();
    }

    public function getPanelParams(array $component, array &$props)
    {
        return array();
    }

    public function getCustomPanelClass(array $component, array &$props)
    {
        return array();
    }
    public function getPanelClass(array $component)
    {
        return '';
    }
    public function getCustomPanelParams(array $component, array &$props)
    {
        $ret = array();

        // $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        // foreach ($this->getSubcomponents($component) as $subComponent) {

        //     $submoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subComponent);
        //     $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($component, $props), $this->getBootstrapcomponentType($component));
        //     $ret[$submoduleOutputName]['data-initjs-targets'] = sprintf(
        //         '%s > %s',
        //         '#'.$frontend_id.'-'.$submoduleOutputName.'-container',
        //         'div.pop-block'
        //     );
        // }

        return $ret;
    }

    public function getPanelactiveClass(array $component)
    {
        return '';
    }

    // protected function getInitjsBlockbranches(array $component, array &$props) {

    //     return array_merge(
    //         parent::getInitjsBlockbranches($component, $props),
    //         $this->getActivemoduleSelectors($component, $props)
    //     );
    // }

    // function getActivemoduleSelectors(array $component, array &$props) {

    //     $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    //     $ret = array();

    //     foreach ($this->getSubcomponents($component) as $subComponent) {

    //         $submoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subComponent);
    //         $frontend_id = PoP_Bootstrap_Utils::getFrontendId(/*$props['block-id']*/$this->getFrontendId($component, $props), $this->getBootstrapcomponentType($component));
    //         $ret[] = sprintf(
    //             '%s > %s > %s',
    //             '#'.$frontend_id.'-'.$submoduleOutputName.'.'.$this->getPanelactiveClass($component),
    //             '#'.$frontend_id.'-'.$submoduleOutputName.'-container',
    //             'div.pop-block'
    //         );
    //     }

    //     return $ret;
    // }

    public function getButtonsClass(array $component, array &$props)
    {
        return array();
    }

    public function isSubmoduleActivePanel(array $component, $subComponent)
    {
        return false;
    }
    public function getActivepanelSubmodule(array $component)
    {
        $subComponents = $this->getSubcomponents($component);
        foreach ($subComponents as $subComponent) {
            if ($this->isSubmoduleActivePanel($component, $subComponent)) {
                return $subComponent;
            }
        }

        if ($this->isMandatoryActivePanel($component)) {
            return $this->getDefaultActivepanelSubmodule($component);
        }

        return null;
    }
    protected function isMandatoryActivePanel(array $component)
    {
        return false;
    }

    public function getDefaultActivepanelSubmodule(array $component)
    {

        // Simply return the first one
        $subComponents = $this->getSubcomponents($component);
        return $subComponents[0];
    }

    public function getPanelTitle(array $component)
    {
        return null;
    }
    public function getPanelHeaderType(array $component)
    {
        return null;
    }
    public function getDropdownTitle(array $component)
    {
        return null;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        $ret = array();

        foreach ($this->getSubcomponents($component) as $subComponent) {
            $ret[] = [
                'header-subcomponent' => $subComponent,
            ];
        }

        return $ret;
    }

    public function getPanelHeaderThumbs(array $component, array &$props)
    {
        return null;
    }
    public function getPanelHeaderTooltips(array $component, array &$props)
    {
        return null;
    }
    public function getPanelHeaderTitles(array $component, array &$props)
    {
        // Comment Leo 19/11/2018: Check this out: initially, this gets the title from the block, but since migrating blocks to dataloads, the processor may not have `getTitle` anymore and the code below explodes
        // $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        // return $componentprocessor_manager->getProcessor($subComponent)->getTitle($subComponent, $props);
        return array();
    }
    public function getPanelheaderClass(array $component)
    {
        return '';
    }
    public function getPanelheaderItemClass(array $component)
    {
        return '';
    }
    public function getPanelheaderParams(array $component)
    {
        return array();
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->getPanelHeaderTooltips($component, $props)) {
            $this->addJsmethod($ret, 'tooltip', 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($panel_components = $this->getPanelSubmodules($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['panels'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $panel_components
            );
        }

        // Fill in all the properties
        if ($panel_header_type = $this->getPanelHeaderType($component)) {
            $ret['panel-header-type'] = $panel_header_type;
            $ret['panel-title'] = $this->getPanelTitle($component);

            $titles = $this->getPanelHeaderTitles($component, $props);
            $thumbs = $this->getPanelHeaderThumbs($component, $props);
            $tooltips = $this->getPanelHeaderTooltips($component, $props);

            $panel_headers = array();
            foreach ($this->getPanelHeaders($component, $props) as $panelHeader) {
                $header_subcomponent = $panelHeader['header-subcomponent'];
                $subheader_subcomponents = $panelHeader['subheader-subcomponents'];
                $headerSubmoduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($header_subcomponent);
                $header = array(
                    'componentoutputname' => \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($header_subcomponent)
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

                if ($subheader_subcomponents) {
                    $subheaders = array();
                    foreach ($subheader_subcomponents as $subheader_subcomponent) {
                        $subheaderSubmoduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($subheader_subcomponent);
                        $subheader = array(
                            'componentoutputname' => \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subheader_subcomponent)
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

            if ($panelheader_class = $this->getPanelheaderClass($component)) {
                $ret[GD_JS_CLASSES]['panelheader'] = $panelheader_class;
            }
            if ($panelheader_item_class = $this->getPanelheaderItemClass($component)) {
                $ret[GD_JS_CLASSES]['panelheader-item'] = $panelheader_item_class;
            }
            if ($panelheader_params = $this->getPanelheaderParams($component)) {
                $ret['panelheader-params'] = $panelheader_params;
            }
        }

        if ($dropdown_title = $this->getDropdownTitle($component)) {
            $ret[GD_JS_TITLES] = array(
                'dropdown' => $dropdown_title
            );
        }

        if ($buttons_class = $this->getButtonsClass($component, $props)) {
            $ret['buttons-class'] = $buttons_class;
        }
        if ($panel_class = $this->getPanelClass($component)) {
            $ret[GD_JS_CLASSES]['panel'] = $panel_class;
        }
        if ($custom_panel_class = $this->getCustomPanelClass($component, $props)) {
            $ret['custom-panel-class'] = $custom_panel_class;
        }
        if ($panel_params = $this->getPanelParams($component, $props)) {
            $ret['panel-params'] = $panel_params;
        }
        if ($custom_panel_params = $this->getCustomPanelParams($component, $props)) {
            $ret['custom-panel-params'] = $custom_panel_params;
        }
        if ($icon = $this->getIcon($component)) {
            $ret['icon'] = $icon;
        }
        if ($body_class = $this->getBodyClass($component)) {
            $ret['body-class'] = $body_class;
        }
        $ret['buttons'] = $this->getButtons($component);

        return $ret;
    }

    public function getMutableonmodelConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonmodelConfiguration($component, $props);

        if ($active_subcomponent = $this->getActivepanelSubmodule($component)) {
            $ret['active'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($active_subcomponent);
        }

        return $ret;
    }

    protected function lazyLoadInactivePanels(array $component, array &$props)
    {
        return false;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($this->lazyLoadInactivePanels($component, $props)) {
            $active_subcomponent = $this->getActivepanelSubmodule($component);
            $inactive_subcomponents = array_diff(
                $this->getSubcomponents($component),
                array(
                    $active_subcomponent
                )
            );
            foreach ($inactive_subcomponents as $subComponent) {
                $this->setProp([$subComponent], $props, 'skip-data-load', true);
            }
        }

        parent::initModelProps($component, $props);
    }

    // function initModelProps(array $component, array &$props) {

    //     $blocktarget = implode(', ', $this->getActivemoduleSelectors($component, $props));
    //     if ($controlgroup_top = $this->getControlgroupTopSubmodule($component)) {
    //         $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
    //     }
    //     if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($component)) {
    //         $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
    //     }

    //     parent::initModelProps($component, $props);
    // }
}
