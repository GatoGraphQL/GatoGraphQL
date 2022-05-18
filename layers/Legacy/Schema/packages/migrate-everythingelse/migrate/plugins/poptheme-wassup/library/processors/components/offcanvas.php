<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Offcanvas extends PoP_Module_Processor_OffcanvasBase
{
    public final const MODULE_OFFCANVAS_HOVER = 'offcanvas-hover';
    public final const MODULE_OFFCANVAS_NAVIGATOR = 'offcanvas-navigator';
    public final const MODULE_OFFCANVAS_SIDE = 'offcanvas-side';
    public final const MODULE_OFFCANVAS_TOP = 'offcanvas-top';
    public final const MODULE_OFFCANVAS_BODYSIDEINFO = 'offcanvas-bodysideinfo';
    public final const MODULE_OFFCANVAS_BACKGROUND = 'offcanvas-background';
    public final const MODULE_OFFCANVAS_BODYTABS = 'offcanvas-bodytabs';
    public final const MODULE_OFFCANVAS_BODY = 'offcanvas-body';

    use PoP_SPA_Module_Processor_PageSections_Trait;

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_OFFCANVAS_HOVER],
            [self::class, self::MODULE_OFFCANVAS_NAVIGATOR],
            [self::class, self::MODULE_OFFCANVAS_SIDE],
            [self::class, self::MODULE_OFFCANVAS_TOP],
            [self::class, self::MODULE_OFFCANVAS_BODYSIDEINFO],
            [self::class, self::MODULE_OFFCANVAS_BACKGROUND],
            [self::class, self::MODULE_OFFCANVAS_BODYTABS],
            [self::class, self::MODULE_OFFCANVAS_BODY],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_BODY:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $componentVariation == $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subComponentVariations = array(
                    self::MODULE_OFFCANVAS_HOVER => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_HOVER],
                    self::MODULE_OFFCANVAS_NAVIGATOR => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_NAVIGATOR],
                    self::MODULE_OFFCANVAS_BODY => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODY],
                );
                $submodule = $subComponentVariations[$componentVariation[1]];

                if ($load_module) {
                    $ret[] = $submodule;
                } else {
                    // Tell the pageSections to have no pages inside
                    $moduleAtts = array('empty' => true);
                    $ret[] = [
                        $submodule[0],
                        $submodule[1],
                        $moduleAtts
                    ];
                }
                break;

            case self::MODULE_OFFCANVAS_BODYTABS:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $dependencies = array(
                        self::MODULE_OFFCANVAS_BODYTABS => [self::class, self::MODULE_OFFCANVAS_BODY],
                        self::MODULE_OFFCANVAS_BODYSIDEINFO => [self::class, self::MODULE_OFFCANVAS_BODY],
                    );
                    $load_module = $dependencies[$componentVariation[1]] == $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subComponentVariations = array(
                    self::MODULE_OFFCANVAS_BODYTABS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODYTABS],
                    self::MODULE_OFFCANVAS_BODYSIDEINFO => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODYSIDEINFO],
                );
                $submodule = $subComponentVariations[$componentVariation[1]];

                if ($load_module) {
                    $ret[] = $submodule;
                } else {
                    // Tell the pageSections to have no pages inside
                    $moduleAtts = array('empty' => true);
                    $ret[] = [
                        $submodule[0],
                        $submodule[1],
                        $moduleAtts
                    ];
                }
                break;

            case self::MODULE_OFFCANVAS_BACKGROUND:
                $ret[] = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BACKGROUND];
                break;

            case self::MODULE_OFFCANVAS_SIDE:
                $ret[] = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_SIDE];
                break;

            case self::MODULE_OFFCANVAS_TOP:
                $ret[] = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_TOP];
                break;
        }

        return $ret;
    }

    protected function getHtmltag(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_TOP:
                return 'header';
        }

        return parent::getHtmltag($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_SIDE:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
            case self::MODULE_OFFCANVAS_BACKGROUND:
                $this->addJsmethod($ret, 'scrollbarVertical');
                break;

            case self::MODULE_OFFCANVAS_BODYTABS:
                $this->addJsmethod($ret, 'scrollbarHorizontal');
                break;

            case self::MODULE_OFFCANVAS_BODY:
                $this->addJsmethod($ret, 'customCloseModals');
                $this->addJsmethod($ret, 'scrollHandler');
                if (PoP_ApplicationProcessors_Utils::addMainpagesectionScrollbar()) {
                    $this->addJsmethod($ret, 'scrollbarVertical');
                }
                break;
        }

        return $ret;
    }

    protected function getWrapperClass(array $componentVariation, array &$props)
    {
        $ret = parent::getWrapperClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_SIDE:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
            case self::MODULE_OFFCANVAS_BACKGROUND:
            case self::MODULE_OFFCANVAS_BODY:
                $ret .= ' container-fluid perfect-scrollbar-offsetreference';
                break;

            case self::MODULE_OFFCANVAS_BODYTABS:
                $ret .= ' perfect-scrollbar-offsetreference';
                break;
        }

        return $ret;
    }

    protected function getContentClass(array $componentVariation, array &$props)
    {
        $ret = parent::getContentClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
            case self::MODULE_OFFCANVAS_BODY:
                $ret .= ' tab-content';
                break;
        }

        return $ret;
    }

    protected function getClosebuttonClass(array $componentVariation, array &$props)
    {
        $ret = parent::getClosebuttonClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
                $ret .= ' close-lg';
                break;
        }

        return $ret;
    }

    protected function getOffcanvasClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_SIDE:
            case self::MODULE_OFFCANVAS_TOP:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
            case self::MODULE_OFFCANVAS_BACKGROUND:
            case self::MODULE_OFFCANVAS_BODYTABS:
            case self::MODULE_OFFCANVAS_BODY:
                $classes = array(
                    self::MODULE_OFFCANVAS_HOVER => 'hover',
                    self::MODULE_OFFCANVAS_NAVIGATOR => 'navigator',
                    self::MODULE_OFFCANVAS_SIDE => 'side',
                    self::MODULE_OFFCANVAS_TOP => 'top',
                    self::MODULE_OFFCANVAS_BODYSIDEINFO => 'sideinfo',
                    self::MODULE_OFFCANVAS_BACKGROUND => 'background',
                    self::MODULE_OFFCANVAS_BODYTABS => 'pagetabs',
                    self::MODULE_OFFCANVAS_BODY => 'body',
                );
                return $classes[$componentVariation[1]];
        }

        return parent::getOffcanvasClass($componentVariation, $props);
    }

    protected function addClosebutton(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
                return true;
        }

        return parent::addClosebutton($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_SIDE:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
            case self::MODULE_OFFCANVAS_BACKGROUND:
                $this->appendProp($componentVariation, $props, 'class', 'pop-waypoints-context scrollable perfect-scrollbar vertical');
                break;

            case self::MODULE_OFFCANVAS_BODYTABS:
                $this->appendProp($componentVariation, $props, 'class', 'pop-waypoints-context scrollable perfect-scrollbar horizontal navbar navbar-main navbar-inverse');
                break;

            case self::MODULE_OFFCANVAS_BODY:
                $scrollable_classes = '';
                if (PoP_ApplicationProcessors_Utils::addMainpagesectionScrollbar()) {
                    $scrollable_classes = 'pop-waypoints-context scrollable perfect-scrollbar vertical';
                }
                $this->appendProp($componentVariation, $props, 'class', $scrollable_classes);
                break;

            case self::MODULE_OFFCANVAS_TOP:
                $this->appendProp($componentVariation, $props, 'class', 'header frame topnav navbar navbar-main navbar-inverse');
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    )
                );
                break;

            case self::MODULE_OFFCANVAS_NAVIGATOR:
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_NAVIGATOR,
                        'data-clickframetarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                        'data-pagesection-openmode' => 'automatic',
                    )
                );
                break;

            case self::MODULE_OFFCANVAS_BODYTABS:
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                        'data-pagesection-openmode' => 'manual',
                    )
                );
                break;

            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
                $openmode = \PoP\Root\App::applyFilters('modules:sideinfo:openmode', 'automatic');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                        'data-pagesection-openmode' => $openmode,
                    )
                );
                break;

            case self::MODULE_OFFCANVAS_BODY:
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


