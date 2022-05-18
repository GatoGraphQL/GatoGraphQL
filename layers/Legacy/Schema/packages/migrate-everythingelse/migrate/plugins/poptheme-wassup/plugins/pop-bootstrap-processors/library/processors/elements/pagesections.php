<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

const POP_COMPONENTID_PAGESECTIONCONTAINERID_HOLE = 'ps-hole';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_FRAMECOMPONENTS = 'ps-framecomponents';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_TOP = 'ps-top';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_SIDE = 'ps-side';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_BACKGROUND = 'ps-background';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_CONTAINER = 'ps-container';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_MODALS = 'ps-modals';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_ADDONTABS = 'ps-addontabs';
const POP_COMPONENTID_PAGESECTIONCONTAINERID_BODYTABS = 'ps-bodytabs';

class PoP_Module_Processor_PageSections extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_PAGESECTION_QUICKVIEW = 'pagesection-quickview';
    public final const COMPONENT_PAGESECTION_QUICKVIEWSIDEINFO = 'pagesection-quickviewsideinfo';
    public final const COMPONENT_PAGESECTION_ADDONTABS = 'pagesection-addontabs';
    public final const COMPONENT_PAGESECTION_BACKGROUND = 'pagesection-background';
    public final const COMPONENT_PAGESECTION_FRAMECOMPONENTS = 'pagesection-framecomponents';
    public final const COMPONENT_PAGESECTION_HOLE = 'pagesection-hole';
    public final const COMPONENT_PAGESECTION_HOVER = 'pagesection-hover';
    public final const COMPONENT_PAGESECTION_NAVIGATOR = 'pagesection-navigator';
    public final const COMPONENT_PAGESECTION_SIDE = 'pagesection-side';
    public final const COMPONENT_PAGESECTION_TOP = 'pagesection-top';
    public final const COMPONENT_PAGESECTION_BODYSIDEINFO = 'pagesection-bodysideinfo';
    public final const COMPONENT_PAGESECTION_MODALS = 'pagesection-modals';
    public final const COMPONENT_PAGESECTION_BODYTABS = 'pagesection-bodytabs';
    public final const COMPONENT_PAGESECTION_BODY = 'pagesection-body';

    use PoP_SPA_Module_Processor_PageSections_Trait;

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_PAGESECTION_QUICKVIEW],
            [self::class, self::COMPONENT_PAGESECTION_QUICKVIEWSIDEINFO],
            [self::class, self::COMPONENT_PAGESECTION_ADDONTABS],
            [self::class, self::COMPONENT_PAGESECTION_BACKGROUND],
            [self::class, self::COMPONENT_PAGESECTION_FRAMECOMPONENTS],
            [self::class, self::COMPONENT_PAGESECTION_HOLE],
            [self::class, self::COMPONENT_PAGESECTION_HOVER],
            [self::class, self::COMPONENT_PAGESECTION_NAVIGATOR],
            [self::class, self::COMPONENT_PAGESECTION_SIDE],
            [self::class, self::COMPONENT_PAGESECTION_TOP],
            [self::class, self::COMPONENT_PAGESECTION_BODYSIDEINFO],
            [self::class, self::COMPONENT_PAGESECTION_MODALS],
            [self::class, self::COMPONENT_PAGESECTION_BODYTABS],
            [self::class, self::COMPONENT_PAGESECTION_BODY],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_QUICKVIEW:
            case self::COMPONENT_PAGESECTION_QUICKVIEWSIDEINFO:
            case self::COMPONENT_PAGESECTION_ADDONTABS:
            case self::COMPONENT_PAGESECTION_HOLE:
            case self::COMPONENT_PAGESECTION_HOVER:
            case self::COMPONENT_PAGESECTION_NAVIGATOR:
            case self::COMPONENT_PAGESECTION_SIDE:
            case self::COMPONENT_PAGESECTION_TOP:
            case self::COMPONENT_PAGESECTION_BODYSIDEINFO:
            case self::COMPONENT_PAGESECTION_MODALS:
            case self::COMPONENT_PAGESECTION_BODYTABS:
            case self::COMPONENT_PAGESECTION_BODY:
                // If not told to be empty, then add the page submodule
                $componentAtts = count($component) >= 3 ? $component[2] : null;
                if (!($componentAtts && $componentAtts['empty'])) {
                    $subComponents = array(
                        self::COMPONENT_PAGESECTION_QUICKVIEW => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_QUICKVIEW],
                        self::COMPONENT_PAGESECTION_QUICKVIEWSIDEINFO => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_QUICKVIEWSIDEINFO],
                        self::COMPONENT_PAGESECTION_ADDONTABS => [PoP_Module_Processor_PageTabs::class, PoP_Module_Processor_PageTabs::COMPONENT_PAGE_ADDONTABS],
                        self::COMPONENT_PAGESECTION_HOLE => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_HOLE],
                        self::COMPONENT_PAGESECTION_HOVER => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_HOVER],
                        self::COMPONENT_PAGESECTION_NAVIGATOR => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_NAVIGATOR],
                        self::COMPONENT_PAGESECTION_SIDE => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_SIDE],
                        self::COMPONENT_PAGESECTION_TOP => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_TOP],
                        self::COMPONENT_PAGESECTION_BODYSIDEINFO => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_BODYSIDEINFO],
                        self::COMPONENT_PAGESECTION_MODALS => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_MODALS],
                        self::COMPONENT_PAGESECTION_BODYTABS => [PoP_Module_Processor_PageTabs::class, PoP_Module_Processor_PageTabs::COMPONENT_PAGE_BODYTABS],
                        self::COMPONENT_PAGESECTION_BODY => [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_BODY],
                    );
                    $ret[] = $subComponents[$component[1]];
                }
                break;

            case self::COMPONENT_PAGESECTION_FRAMECOMPONENTS:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subComponent = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_FRAMECOMPONENTS];
                $componentAtts = array();

                // Requested a different target: load nothing
                if (!$load_component) {
                    $componentAtts['onlyinitial'] = true;
                }
                if ($componentAtts) {
                    $ret[] = [
                        $subComponent[0],
                        $subComponent[1],
                        $componentAtts
                    ];
                } else {
                    $ret[] = $subComponent;
                }
                break;

            case self::COMPONENT_PAGESECTION_BACKGROUND:
                $ret[] = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_BACKGROUND];
                break;
        }

        return $ret;
    }

    public function getID(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_HOVER:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_HOVER;

            case self::COMPONENT_PAGESECTION_NAVIGATOR:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_NAVIGATOR;

            case self::COMPONENT_PAGESECTION_SIDE:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_SIDE;

            case self::COMPONENT_PAGESECTION_TOP:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_TOP;

            case self::COMPONENT_PAGESECTION_ADDONTABS:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_ADDONTABS;

            case self::COMPONENT_PAGESECTION_BODYSIDEINFO:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_BODYSIDEINFO;

            case self::COMPONENT_PAGESECTION_QUICKVIEWSIDEINFO:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_QUICKVIEWSIDEINFO;

            case self::COMPONENT_PAGESECTION_BODY:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY;

            case self::COMPONENT_PAGESECTION_QUICKVIEW:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_QUICKVIEW;

            case self::COMPONENT_PAGESECTION_BACKGROUND:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_BACKGROUND;

            case self::COMPONENT_PAGESECTION_FRAMECOMPONENTS:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_FRAMECOMPONENTS;

            case self::COMPONENT_PAGESECTION_HOLE:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_HOLE;

            case self::COMPONENT_PAGESECTION_MODALS:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_MODALS;

            case self::COMPONENT_PAGESECTION_BODYTABS:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_BODYTABS;
        }

        return parent::getID($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_QUICKVIEW:
                $this->appendProp($component, $props, 'class', 'offcanvas body tab-content');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_QUICKVIEW,
                        'data-offcanvas' => 'body',
                    )
                );
                break;

            case self::COMPONENT_PAGESECTION_QUICKVIEWSIDEINFO:
                $this->appendProp($component, $props, 'class', 'offcanvas sideinfo tab-content');
                $this->mergeProp(
                    $component,
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

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_FRAMECOMPONENTS:
                $this->appendProp($component, $props, 'class', 'framecomponents');
                break;

            case self::COMPONENT_PAGESECTION_HOLE:
                $this->appendProp($component, $props, 'class', 'hole');
                break;

            case self::COMPONENT_PAGESECTION_MODALS:
                $this->appendProp($component, $props, 'class', 'modals');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-frametarget' => POP_TARGET_MODALS,
                    )
                );
                break;
        }

        // The module must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadModule` can work
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);
        $module_props = array(
            $moduleFullName => &$props[$moduleFullName],
        );
        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_BODYSIDEINFO:
                // Allow the Sideinfo's permanent Events Calendar to be lazy-load
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:sideinfo',
                    $component,
                    array(&$module_props),
                    $this
                );
                break;

            case self::COMPONENT_PAGESECTION_BODY:
                // Allow for compatibility for the Users Carousel in the Homepage to not be lazy-load
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:main',
                    $component,
                    array(&$module_props),
                    $this
                );
                break;

            case self::COMPONENT_PAGESECTION_HOVER:
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:hover',
                    $component,
                    array(&$module_props),
                    $this
                );
                break;

            case self::COMPONENT_PAGESECTION_MODALS:
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomModalPageSections:get_props_block_initial:modals',
                    $component,
                    array(&$props),
                    $this
                );
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_ADDONTABS:
                $this->addJsmethod($ret, 'scrollbarHorizontal');
                break;
        }

        return $ret;
    }
}



