<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

const POP_MODULEID_PAGESECTIONCONTAINERID_HOLE = 'ps-hole';
const POP_MODULEID_PAGESECTIONCONTAINERID_FRAMECOMPONENTS = 'ps-framecomponents';
const POP_MODULEID_PAGESECTIONCONTAINERID_TOP = 'ps-top';
const POP_MODULEID_PAGESECTIONCONTAINERID_SIDE = 'ps-side';
const POP_MODULEID_PAGESECTIONCONTAINERID_BACKGROUND = 'ps-background';
const POP_MODULEID_PAGESECTIONCONTAINERID_CONTAINER = 'ps-container';
const POP_MODULEID_PAGESECTIONCONTAINERID_MODALS = 'ps-modals';
const POP_MODULEID_PAGESECTIONCONTAINERID_ADDONTABS = 'ps-addontabs';
const POP_MODULEID_PAGESECTIONCONTAINERID_BODYTABS = 'ps-bodytabs';

class PoP_Module_Processor_PageSections extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_PAGESECTION_QUICKVIEW = 'pagesection-quickview';
    public const MODULE_PAGESECTION_QUICKVIEWSIDEINFO = 'pagesection-quickviewsideinfo';
    public const MODULE_PAGESECTION_ADDONTABS = 'pagesection-addontabs';
    public const MODULE_PAGESECTION_BACKGROUND = 'pagesection-background';
    public const MODULE_PAGESECTION_FRAMECOMPONENTS = 'pagesection-framecomponents';
    public const MODULE_PAGESECTION_HOLE = 'pagesection-hole';
    public const MODULE_PAGESECTION_HOVER = 'pagesection-hover';
    public const MODULE_PAGESECTION_NAVIGATOR = 'pagesection-navigator';
    public const MODULE_PAGESECTION_SIDE = 'pagesection-side';
    public const MODULE_PAGESECTION_TOP = 'pagesection-top';
    public const MODULE_PAGESECTION_BODYSIDEINFO = 'pagesection-bodysideinfo';
    public const MODULE_PAGESECTION_MODALS = 'pagesection-modals';
    public const MODULE_PAGESECTION_BODYTABS = 'pagesection-bodytabs';
    public const MODULE_PAGESECTION_BODY = 'pagesection-body';

    use PoP_SPA_Module_Processor_PageSections_Trait;

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGESECTION_QUICKVIEW],
            [self::class, self::MODULE_PAGESECTION_QUICKVIEWSIDEINFO],
            [self::class, self::MODULE_PAGESECTION_ADDONTABS],
            [self::class, self::MODULE_PAGESECTION_BACKGROUND],
            [self::class, self::MODULE_PAGESECTION_FRAMECOMPONENTS],
            [self::class, self::MODULE_PAGESECTION_HOLE],
            [self::class, self::MODULE_PAGESECTION_HOVER],
            [self::class, self::MODULE_PAGESECTION_NAVIGATOR],
            [self::class, self::MODULE_PAGESECTION_SIDE],
            [self::class, self::MODULE_PAGESECTION_TOP],
            [self::class, self::MODULE_PAGESECTION_BODYSIDEINFO],
            [self::class, self::MODULE_PAGESECTION_MODALS],
            [self::class, self::MODULE_PAGESECTION_BODYTABS],
            [self::class, self::MODULE_PAGESECTION_BODY],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_PAGESECTION_QUICKVIEW:
            case self::MODULE_PAGESECTION_QUICKVIEWSIDEINFO:
            case self::MODULE_PAGESECTION_ADDONTABS:
            case self::MODULE_PAGESECTION_HOLE:
            case self::MODULE_PAGESECTION_HOVER:
            case self::MODULE_PAGESECTION_NAVIGATOR:
            case self::MODULE_PAGESECTION_SIDE:
            case self::MODULE_PAGESECTION_TOP:
            case self::MODULE_PAGESECTION_BODYSIDEINFO:
            case self::MODULE_PAGESECTION_MODALS:
            case self::MODULE_PAGESECTION_BODYTABS:
            case self::MODULE_PAGESECTION_BODY:
                // If not told to be empty, then add the page submodule
                $moduleAtts = count($module) >= 3 ? $module[2] : null;
                if (!($moduleAtts && $moduleAtts['empty'])) {
                    $submodules = array(
                        self::MODULE_PAGESECTION_QUICKVIEW => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_QUICKVIEW],
                        self::MODULE_PAGESECTION_QUICKVIEWSIDEINFO => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_QUICKVIEWSIDEINFO],
                        self::MODULE_PAGESECTION_ADDONTABS => [PoP_Module_Processor_PageTabs::class, PoP_Module_Processor_PageTabs::MODULE_PAGE_ADDONTABS],
                        self::MODULE_PAGESECTION_HOLE => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_HOLE],
                        self::MODULE_PAGESECTION_HOVER => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_HOVER],
                        self::MODULE_PAGESECTION_NAVIGATOR => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_NAVIGATOR],
                        self::MODULE_PAGESECTION_SIDE => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_SIDE],
                        self::MODULE_PAGESECTION_TOP => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_TOP],
                        self::MODULE_PAGESECTION_BODYSIDEINFO => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_BODYSIDEINFO],
                        self::MODULE_PAGESECTION_MODALS => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_MODALS],
                        self::MODULE_PAGESECTION_BODYTABS => [PoP_Module_Processor_PageTabs::class, PoP_Module_Processor_PageTabs::MODULE_PAGE_BODYTABS],
                        self::MODULE_PAGESECTION_BODY => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_BODY],
                    );
                    $ret[] = $submodules[$module[1]];
                }
                break;

            case self::MODULE_PAGESECTION_FRAMECOMPONENTS:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $module == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $submodule = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_FRAMECOMPONENTS];
                $moduleAtts = array();

                // Requested a different target: load nothing
                if (!$load_module) {
                    $moduleAtts['onlyinitial'] = true;
                }
                if ($moduleAtts) {
                    $ret[] = [
                        $submodule[0],
                        $submodule[1],
                        $moduleAtts
                    ];
                } else {
                    $ret[] = $submodule;
                }
                break;

            case self::MODULE_PAGESECTION_BACKGROUND:
                $ret[] = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_BACKGROUND];
                break;
        }

        return $ret;
    }

    public function getID(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_PAGESECTION_HOVER:
                return POP_MODULEID_PAGESECTIONCONTAINERID_HOVER;

            case self::MODULE_PAGESECTION_NAVIGATOR:
                return POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR;

            case self::MODULE_PAGESECTION_SIDE:
                return POP_MODULEID_PAGESECTIONCONTAINERID_SIDE;

            case self::MODULE_PAGESECTION_TOP:
                return POP_MODULEID_PAGESECTIONCONTAINERID_TOP;

            case self::MODULE_PAGESECTION_ADDONTABS:
                return POP_MODULEID_PAGESECTIONCONTAINERID_ADDONTABS;

            case self::MODULE_PAGESECTION_BODYSIDEINFO:
                return POP_MODULEID_PAGESECTIONCONTAINERID_BODYSIDEINFO;

            case self::MODULE_PAGESECTION_QUICKVIEWSIDEINFO:
                return POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEWSIDEINFO;

            case self::MODULE_PAGESECTION_BODY:
                return POP_MODULEID_PAGESECTIONCONTAINERID_BODY;

            case self::MODULE_PAGESECTION_QUICKVIEW:
                return POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEW;

            case self::MODULE_PAGESECTION_BACKGROUND:
                return POP_MODULEID_PAGESECTIONCONTAINERID_BACKGROUND;

            case self::MODULE_PAGESECTION_FRAMECOMPONENTS:
                return POP_MODULEID_PAGESECTIONCONTAINERID_FRAMECOMPONENTS;

            case self::MODULE_PAGESECTION_HOLE:
                return POP_MODULEID_PAGESECTIONCONTAINERID_HOLE;

            case self::MODULE_PAGESECTION_MODALS:
                return POP_MODULEID_PAGESECTIONCONTAINERID_MODALS;

            case self::MODULE_PAGESECTION_BODYTABS:
                return POP_MODULEID_PAGESECTIONCONTAINERID_BODYTABS;
        }

        return parent::getID($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_PAGESECTION_QUICKVIEW:
                $this->appendProp($module, $props, 'class', 'offcanvas body tab-content');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_QUICKVIEW,
                        'data-offcanvas' => 'body',
                    )
                );
                break;

            case self::MODULE_PAGESECTION_QUICKVIEWSIDEINFO:
                $this->appendProp($module, $props, 'class', 'offcanvas sideinfo tab-content');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_QUICKVIEW,
                        'data-offcanvas' => 'sideinfo',
                        'data-pagesection-openmode' => 'manual',
                    )
                );
                break;
        }

        switch ($module[1]) {
            case self::MODULE_PAGESECTION_FRAMECOMPONENTS:
                $this->appendProp($module, $props, 'class', 'framecomponents');
                break;

            case self::MODULE_PAGESECTION_HOLE:
                $this->appendProp($module, $props, 'class', 'hole');
                break;

            case self::MODULE_PAGESECTION_MODALS:
                $this->appendProp($module, $props, 'class', 'modals');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_MODALS,
                    )
                );
                break;
        }

        // The module must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadModule` can work
        $moduleFullName = ModuleUtils::getModuleFullName($module);
        $module_props = array(
            $moduleFullName => &$props[$moduleFullName],
        );
        switch ($module[1]) {
            case self::MODULE_PAGESECTION_BODYSIDEINFO:
                // Allow the Sideinfo's permanent Events Calendar to be lazy-load
                \PoP\Root\App::getHookManager()->doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:sideinfo',
                    $module,
                    array(&$module_props),
                    $this
                );
                break;

            case self::MODULE_PAGESECTION_BODY:
                // Allow for compatibility for the Users Carousel in the Homepage to not be lazy-load
                \PoP\Root\App::getHookManager()->doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:main',
                    $module,
                    array(&$module_props),
                    $this
                );
                break;

            case self::MODULE_PAGESECTION_HOVER:
                \PoP\Root\App::getHookManager()->doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:hover',
                    $module,
                    array(&$module_props),
                    $this
                );
                break;

            case self::MODULE_PAGESECTION_MODALS:
                \PoP\Root\App::getHookManager()->doAction(
                    'PoP_Module_Processor_CustomModalPageSections:get_props_block_initial:modals',
                    $module,
                    array(&$props),
                    $this
                );
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_PAGESECTION_ADDONTABS:
                $this->addJsmethod($ret, 'scrollbarHorizontal');
                break;
        }

        return $ret;
    }
}



