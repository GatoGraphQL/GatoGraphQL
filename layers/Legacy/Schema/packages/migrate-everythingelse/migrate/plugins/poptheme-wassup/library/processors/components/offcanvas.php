<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

class PoP_Module_Processor_Offcanvas extends PoP_Module_Processor_OffcanvasBase
{
    public const MODULE_OFFCANVAS_HOVER = 'offcanvas-hover';
    public const MODULE_OFFCANVAS_NAVIGATOR = 'offcanvas-navigator';
    public const MODULE_OFFCANVAS_SIDE = 'offcanvas-side';
    public const MODULE_OFFCANVAS_TOP = 'offcanvas-top';
    public const MODULE_OFFCANVAS_BODYSIDEINFO = 'offcanvas-bodysideinfo';
    public const MODULE_OFFCANVAS_BACKGROUND = 'offcanvas-background';
    public const MODULE_OFFCANVAS_BODYTABS = 'offcanvas-bodytabs';
    public const MODULE_OFFCANVAS_BODY = 'offcanvas-body';

    use PoP_SPA_Module_Processor_PageSections_Trait;

    public function getModulesToProcess(): array
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

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_BODY:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $module == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $submodules = array(
                    self::MODULE_OFFCANVAS_HOVER => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_HOVER],
                    self::MODULE_OFFCANVAS_NAVIGATOR => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_NAVIGATOR],
                    self::MODULE_OFFCANVAS_BODY => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODY],
                );
                $submodule = $submodules[$module[1]];

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
                    $load_module = $dependencies[$module[1]] == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $submodules = array(
                    self::MODULE_OFFCANVAS_BODYTABS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODYTABS],
                    self::MODULE_OFFCANVAS_BODYSIDEINFO => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODYSIDEINFO],
                );
                $submodule = $submodules[$module[1]];

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

    protected function getHtmltag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_TOP:
                return 'header';
        }

        return parent::getHtmltag($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
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

    protected function getWrapperClass(array $module, array &$props)
    {
        $ret = parent::getWrapperClass($module, $props);

        switch ($module[1]) {
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

    protected function getContentClass(array $module, array &$props)
    {
        $ret = parent::getContentClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
            case self::MODULE_OFFCANVAS_BODY:
                $ret .= ' tab-content';
                break;
        }

        return $ret;
    }

    protected function getClosebuttonClass(array $module, array &$props)
    {
        $ret = parent::getClosebuttonClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
                $ret .= ' close-lg';
                break;
        }

        return $ret;
    }

    protected function getOffcanvasClass(array $module, array &$props)
    {
        switch ($module[1]) {
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
                return $classes[$module[1]];
        }

        return parent::getOffcanvasClass($module, $props);
    }

    protected function addClosebutton(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
                return true;
        }

        return parent::addClosebutton($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
            case self::MODULE_OFFCANVAS_NAVIGATOR:
            case self::MODULE_OFFCANVAS_SIDE:
            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
            case self::MODULE_OFFCANVAS_BACKGROUND:
                $this->appendProp($module, $props, 'class', 'pop-waypoints-context scrollable perfect-scrollbar vertical');
                break;

            case self::MODULE_OFFCANVAS_BODYTABS:
                $this->appendProp($module, $props, 'class', 'pop-waypoints-context scrollable perfect-scrollbar horizontal navbar navbar-main navbar-inverse');
                break;

            case self::MODULE_OFFCANVAS_BODY:
                $scrollable_classes = '';
                if (PoP_ApplicationProcessors_Utils::addMainpagesectionScrollbar()) {
                    $scrollable_classes = 'pop-waypoints-context scrollable perfect-scrollbar vertical';
                }
                $this->appendProp($module, $props, 'class', $scrollable_classes);
                break;

            case self::MODULE_OFFCANVAS_TOP:
                $this->appendProp($module, $props, 'class', 'header frame topnav navbar navbar-main navbar-inverse');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_OFFCANVAS_HOVER:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    )
                );
                break;

            case self::MODULE_OFFCANVAS_NAVIGATOR:
                $this->mergeProp(
                    $module,
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
                    $module,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                        'data-pagesection-openmode' => 'manual',
                    )
                );
                break;

            case self::MODULE_OFFCANVAS_BODYSIDEINFO:
                $openmode = \PoP\Root\App::getHookManager()->applyFilters('modules:sideinfo:openmode', 'automatic');
                $this->mergeProp(
                    $module,
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
                    $module,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
}


