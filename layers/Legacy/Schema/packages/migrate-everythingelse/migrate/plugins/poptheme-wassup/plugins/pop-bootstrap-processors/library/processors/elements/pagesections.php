<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

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
    public final const MODULE_PAGESECTION_QUICKVIEW = 'pagesection-quickview';
    public final const MODULE_PAGESECTION_QUICKVIEWSIDEINFO = 'pagesection-quickviewsideinfo';
    public final const MODULE_PAGESECTION_ADDONTABS = 'pagesection-addontabs';
    public final const MODULE_PAGESECTION_BACKGROUND = 'pagesection-background';
    public final const MODULE_PAGESECTION_FRAMECOMPONENTS = 'pagesection-framecomponents';
    public final const MODULE_PAGESECTION_HOLE = 'pagesection-hole';
    public final const MODULE_PAGESECTION_HOVER = 'pagesection-hover';
    public final const MODULE_PAGESECTION_NAVIGATOR = 'pagesection-navigator';
    public final const MODULE_PAGESECTION_SIDE = 'pagesection-side';
    public final const MODULE_PAGESECTION_TOP = 'pagesection-top';
    public final const MODULE_PAGESECTION_BODYSIDEINFO = 'pagesection-bodysideinfo';
    public final const MODULE_PAGESECTION_MODALS = 'pagesection-modals';
    public final const MODULE_PAGESECTION_BODYTABS = 'pagesection-bodytabs';
    public final const MODULE_PAGESECTION_BODY = 'pagesection-body';

    use PoP_SPA_Module_Processor_PageSections_Trait;

    public function getComponentVariationsToProcess(): array
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

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
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
                $moduleAtts = count($componentVariation) >= 3 ? $componentVariation[2] : null;
                if (!($moduleAtts && $moduleAtts['empty'])) {
                    $subComponentVariations = array(
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
                    $ret[] = $subComponentVariations[$componentVariation[1]];
                }
                break;

            case self::MODULE_PAGESECTION_FRAMECOMPONENTS:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $componentVariation == $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subComponentVariation = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_FRAMECOMPONENTS];
                $moduleAtts = array();

                // Requested a different target: load nothing
                if (!$load_module) {
                    $moduleAtts['onlyinitial'] = true;
                }
                if ($moduleAtts) {
                    $ret[] = [
                        $subComponentVariation[0],
                        $subComponentVariation[1],
                        $moduleAtts
                    ];
                } else {
                    $ret[] = $subComponentVariation;
                }
                break;

            case self::MODULE_PAGESECTION_BACKGROUND:
                $ret[] = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_BACKGROUND];
                break;
        }

        return $ret;
    }

    public function getID(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
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

        return parent::getID($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_QUICKVIEW:
                $this->appendProp($componentVariation, $props, 'class', 'offcanvas body tab-content');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_QUICKVIEW,
                        'data-offcanvas' => 'body',
                    )
                );
                break;

            case self::MODULE_PAGESECTION_QUICKVIEWSIDEINFO:
                $this->appendProp($componentVariation, $props, 'class', 'offcanvas sideinfo tab-content');
                $this->mergeProp(
                    $componentVariation,
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

        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_FRAMECOMPONENTS:
                $this->appendProp($componentVariation, $props, 'class', 'framecomponents');
                break;

            case self::MODULE_PAGESECTION_HOLE:
                $this->appendProp($componentVariation, $props, 'class', 'hole');
                break;

            case self::MODULE_PAGESECTION_MODALS:
                $this->appendProp($componentVariation, $props, 'class', 'modals');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_MODALS,
                    )
                );
                break;
        }

        // The module must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadModule` can work
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
        $module_props = array(
            $moduleFullName => &$props[$moduleFullName],
        );
        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_BODYSIDEINFO:
                // Allow the Sideinfo's permanent Events Calendar to be lazy-load
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:sideinfo',
                    $componentVariation,
                    array(&$module_props),
                    $this
                );
                break;

            case self::MODULE_PAGESECTION_BODY:
                // Allow for compatibility for the Users Carousel in the Homepage to not be lazy-load
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:main',
                    $componentVariation,
                    array(&$module_props),
                    $this
                );
                break;

            case self::MODULE_PAGESECTION_HOVER:
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:hover',
                    $componentVariation,
                    array(&$module_props),
                    $this
                );
                break;

            case self::MODULE_PAGESECTION_MODALS:
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomModalPageSections:get_props_block_initial:modals',
                    $componentVariation,
                    array(&$props),
                    $this
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_ADDONTABS:
                $this->addJsmethod($ret, 'scrollbarHorizontal');
                break;
        }

        return $ret;
    }
}



