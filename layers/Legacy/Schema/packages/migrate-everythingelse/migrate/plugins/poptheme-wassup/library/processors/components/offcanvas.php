<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Offcanvas extends PoP_Module_Processor_OffcanvasBase
{
    public final const COMPONENT_OFFCANVAS_HOVER = 'offcanvas-hover';
    public final const COMPONENT_OFFCANVAS_NAVIGATOR = 'offcanvas-navigator';
    public final const COMPONENT_OFFCANVAS_SIDE = 'offcanvas-side';
    public final const COMPONENT_OFFCANVAS_TOP = 'offcanvas-top';
    public final const COMPONENT_OFFCANVAS_BODYSIDEINFO = 'offcanvas-bodysideinfo';
    public final const COMPONENT_OFFCANVAS_BACKGROUND = 'offcanvas-background';
    public final const COMPONENT_OFFCANVAS_BODYTABS = 'offcanvas-bodytabs';
    public final const COMPONENT_OFFCANVAS_BODY = 'offcanvas-body';

    use PoP_SPA_Module_Processor_PageSections_Trait;

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_OFFCANVAS_HOVER,
            self::COMPONENT_OFFCANVAS_NAVIGATOR,
            self::COMPONENT_OFFCANVAS_SIDE,
            self::COMPONENT_OFFCANVAS_TOP,
            self::COMPONENT_OFFCANVAS_BODYSIDEINFO,
            self::COMPONENT_OFFCANVAS_BACKGROUND,
            self::COMPONENT_OFFCANVAS_BODYTABS,
            self::COMPONENT_OFFCANVAS_BODY,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
            case self::COMPONENT_OFFCANVAS_BODY:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subcomponents = array(
                    self::COMPONENT_OFFCANVAS_HOVER => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_HOVER],
                    self::COMPONENT_OFFCANVAS_NAVIGATOR => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_NAVIGATOR],
                    self::COMPONENT_OFFCANVAS_BODY => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_BODY],
                );
                $subcomponent = $subcomponents[$component->name];

                if ($load_component) {
                    $ret[] = $subcomponent;
                } else {
                    // Tell the pageSections to have no pages inside
                    $componentAtts = array('empty' => true);
                    $ret[] = [
                        $subcomponent[0],
                        $subcomponent[1],
                        $componentAtts
                    ];
                }
                break;

            case self::COMPONENT_OFFCANVAS_BODYTABS:
            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $dependencies = array(
                        self::COMPONENT_OFFCANVAS_BODYTABS => self::COMPONENT_OFFCANVAS_BODY,
                        self::COMPONENT_OFFCANVAS_BODYSIDEINFO => self::COMPONENT_OFFCANVAS_BODY,
                    );
                    $load_component = $dependencies[$component->name] == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subcomponents = array(
                    self::COMPONENT_OFFCANVAS_BODYTABS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_BODYTABS],
                    self::COMPONENT_OFFCANVAS_BODYSIDEINFO => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_BODYSIDEINFO],
                );
                $subcomponent = $subcomponents[$component->name];

                if ($load_component) {
                    $ret[] = $subcomponent;
                } else {
                    // Tell the pageSections to have no pages inside
                    $componentAtts = array('empty' => true);
                    $ret[] = [
                        $subcomponent[0],
                        $subcomponent[1],
                        $componentAtts
                    ];
                }
                break;

            case self::COMPONENT_OFFCANVAS_BACKGROUND:
                $ret[] = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_BACKGROUND];
                break;

            case self::COMPONENT_OFFCANVAS_SIDE:
                $ret[] = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_SIDE];
                break;

            case self::COMPONENT_OFFCANVAS_TOP:
                $ret[] = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_TOP];
                break;
        }

        return $ret;
    }

    protected function getHtmltag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_TOP:
                return 'header';
        }

        return parent::getHtmltag($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
            case self::COMPONENT_OFFCANVAS_SIDE:
            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
            case self::COMPONENT_OFFCANVAS_BACKGROUND:
                $this->addJsmethod($ret, 'scrollbarVertical');
                break;

            case self::COMPONENT_OFFCANVAS_BODYTABS:
                $this->addJsmethod($ret, 'scrollbarHorizontal');
                break;

            case self::COMPONENT_OFFCANVAS_BODY:
                $this->addJsmethod($ret, 'customCloseModals');
                $this->addJsmethod($ret, 'scrollHandler');
                if (PoP_ApplicationProcessors_Utils::addMainpagesectionScrollbar()) {
                    $this->addJsmethod($ret, 'scrollbarVertical');
                }
                break;
        }

        return $ret;
    }

    protected function getWrapperClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getWrapperClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
            case self::COMPONENT_OFFCANVAS_SIDE:
            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
            case self::COMPONENT_OFFCANVAS_BACKGROUND:
            case self::COMPONENT_OFFCANVAS_BODY:
                $ret .= ' container-fluid perfect-scrollbar-offsetreference';
                break;

            case self::COMPONENT_OFFCANVAS_BODYTABS:
                $ret .= ' perfect-scrollbar-offsetreference';
                break;
        }

        return $ret;
    }

    protected function getContentClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getContentClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
            case self::COMPONENT_OFFCANVAS_BODY:
                $ret .= ' tab-content';
                break;
        }

        return $ret;
    }

    protected function getClosebuttonClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getClosebuttonClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
                $ret .= ' close-lg';
                break;
        }

        return $ret;
    }

    protected function getOffcanvasClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
            case self::COMPONENT_OFFCANVAS_SIDE:
            case self::COMPONENT_OFFCANVAS_TOP:
            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
            case self::COMPONENT_OFFCANVAS_BACKGROUND:
            case self::COMPONENT_OFFCANVAS_BODYTABS:
            case self::COMPONENT_OFFCANVAS_BODY:
                $classes = array(
                    self::COMPONENT_OFFCANVAS_HOVER => 'hover',
                    self::COMPONENT_OFFCANVAS_NAVIGATOR => 'navigator',
                    self::COMPONENT_OFFCANVAS_SIDE => 'side',
                    self::COMPONENT_OFFCANVAS_TOP => 'top',
                    self::COMPONENT_OFFCANVAS_BODYSIDEINFO => 'sideinfo',
                    self::COMPONENT_OFFCANVAS_BACKGROUND => 'background',
                    self::COMPONENT_OFFCANVAS_BODYTABS => 'pagetabs',
                    self::COMPONENT_OFFCANVAS_BODY => 'body',
                );
                return $classes[$component->name];
        }

        return parent::getOffcanvasClass($component, $props);
    }

    protected function addClosebutton(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
                return true;
        }

        return parent::addClosebutton($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
            case self::COMPONENT_OFFCANVAS_SIDE:
            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
            case self::COMPONENT_OFFCANVAS_BACKGROUND:
                $this->appendProp($component, $props, 'class', 'pop-waypoints-context scrollable perfect-scrollbar vertical');
                break;

            case self::COMPONENT_OFFCANVAS_BODYTABS:
                $this->appendProp($component, $props, 'class', 'pop-waypoints-context scrollable perfect-scrollbar horizontal navbar navbar-main navbar-inverse');
                break;

            case self::COMPONENT_OFFCANVAS_BODY:
                $scrollable_classes = '';
                if (PoP_ApplicationProcessors_Utils::addMainpagesectionScrollbar()) {
                    $scrollable_classes = 'pop-waypoints-context scrollable perfect-scrollbar vertical';
                }
                $this->appendProp($component, $props, 'class', $scrollable_classes);
                break;

            case self::COMPONENT_OFFCANVAS_TOP:
                $this->appendProp($component, $props, 'class', 'header frame topnav navbar navbar-main navbar-inverse');
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_OFFCANVAS_HOVER:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    )
                );
                break;

            case self::COMPONENT_OFFCANVAS_NAVIGATOR:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_NAVIGATOR,
                        'data-clickframetarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                        'data-pagesection-openmode' => 'automatic',
                    )
                );
                break;

            case self::COMPONENT_OFFCANVAS_BODYTABS:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                        'data-pagesection-openmode' => 'manual',
                    )
                );
                break;

            case self::COMPONENT_OFFCANVAS_BODYSIDEINFO:
                $openmode = \PoP\Root\App::applyFilters('modules:sideinfo:openmode', 'automatic');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                        'data-pagesection-openmode' => $openmode,
                    )
                );
                break;

            case self::COMPONENT_OFFCANVAS_BODY:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    )
                );
                break;
        }

        parent::initModelProps($component, $props);
    }
}


