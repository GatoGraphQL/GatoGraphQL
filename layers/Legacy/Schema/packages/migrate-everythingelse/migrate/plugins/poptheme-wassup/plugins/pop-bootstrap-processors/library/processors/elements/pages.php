<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\SPA\Modules\PageInterface;

class PoP_Module_Processor_Pages extends PoPTheme_Wassup_Module_Processor_MultiplePageBase implements PageInterface
{
    public final const MODULE_PAGE_QUICKVIEW = 'page-quickview';
    public final const MODULE_PAGE_QUICKVIEWSIDEINFO = 'page-quickviewsideinfo';
    public final const MODULE_PAGE_ADDONS = 'page-addons';
    public final const MODULE_PAGE_BACKGROUND = 'page-background';
    public final const MODULE_PAGE_FRAMECOMPONENTS = 'page-framecomponents';
    public final const MODULE_PAGE_HOLE = 'page-hole';
    public final const MODULE_PAGE_HOVER = 'page-hover';
    public final const MODULE_PAGE_NAVIGATOR = 'page-navigator';
    public final const MODULE_PAGE_MODALS = 'page-modals';
    public final const MODULE_PAGE_SIDE = 'page-side';
    public final const MODULE_PAGE_TOP = 'page-top';
    public final const MODULE_PAGE_BODYSIDEINFO = 'page-bodysideinfo';
    public final const MODULE_PAGE_BODY = 'page-body';
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_PAGE_QUICKVIEW],
            [self::class, self::COMPONENT_PAGE_QUICKVIEWSIDEINFO],
            [self::class, self::COMPONENT_PAGE_ADDONS],
            [self::class, self::COMPONENT_PAGE_BACKGROUND],
            [self::class, self::COMPONENT_PAGE_FRAMECOMPONENTS],
            [self::class, self::COMPONENT_PAGE_HOLE],
            [self::class, self::COMPONENT_PAGE_HOVER],
            [self::class, self::COMPONENT_PAGE_NAVIGATOR],
            [self::class, self::COMPONENT_PAGE_MODALS],
            [self::class, self::COMPONENT_PAGE_SIDE],
            [self::class, self::COMPONENT_PAGE_TOP],
            [self::class, self::COMPONENT_PAGE_BODYSIDEINFO],
            [self::class, self::COMPONENT_PAGE_BODY],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_PAGE_ADDONS:
            case self::COMPONENT_PAGE_QUICKVIEW:
            case self::COMPONENT_PAGE_QUICKVIEWSIDEINFO:
            case self::COMPONENT_PAGE_BACKGROUND:
            case self::COMPONENT_PAGE_HOLE:
            case self::COMPONENT_PAGE_HOVER:
            case self::COMPONENT_PAGE_NAVIGATOR:
            case self::COMPONENT_PAGE_MODALS:
            case self::COMPONENT_PAGE_SIDE:
            case self::COMPONENT_PAGE_TOP:
            case self::COMPONENT_PAGE_BODYSIDEINFO:
            case self::COMPONENT_PAGE_BODY:
                $module_groups = array(
                    self::COMPONENT_PAGE_ADDONS => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::COMPONENT_PAGE_QUICKVIEW => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::COMPONENT_PAGE_QUICKVIEWSIDEINFO => POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT,
                    self::COMPONENT_PAGE_BACKGROUND => POP_PAGEMODULEGROUP_PAGESECTION_BACKGROUNDFRAMECONTENT,
                    self::COMPONENT_PAGE_HOLE => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::COMPONENT_PAGE_HOVER => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::COMPONENT_PAGE_NAVIGATOR => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::COMPONENT_PAGE_MODALS => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::COMPONENT_PAGE_SIDE => POP_PAGEMODULEGROUP_PAGESECTION_SIDEFRAMECONTENT,
                    self::COMPONENT_PAGE_TOP => POP_PAGEMODULEGROUP_PAGESECTION_TOPFRAMECONTENT,
                    self::COMPONENT_PAGE_BODYSIDEINFO => POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT,
                    self::COMPONENT_PAGE_BODY => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                );
                if ($page_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties($module_groups[$component[1]] ?? null)) {
                    $ret[] = $page_component;
                }
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_PAGE_FRAMECOMPONENTS:
                $componentAtts = count($component) >= 3 ? $component[2] : null;
                if (!$componentAtts || !$componentAtts['onlyinitial']) {
                     // Load targeted module
                    if ($page_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT)) {
                        $ret[] = $page_component;
                    }
                }

                // Load initial frame components
                $ret = array_merge(
                    $ret,
                    RequestUtils::getFramecomponentComponents()
                );
                break;
        }

        return $ret;
    }

    public function getFrametopoptionsSubmodules(array $component): array
    {
        $ret = parent::getFrametopoptionsSubmodules($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_PAGE_BODY:
            case self::COMPONENT_PAGE_QUICKVIEW:
                if ($pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT)) {
                     // Add only if the corresponding content module exists
                    $groups = array(
                        self::COMPONENT_PAGE_BODY => POP_PAGEMODULEGROUP_PAGESECTION_BODYFRAMETOPOPTIONS,
                        self::COMPONENT_PAGE_QUICKVIEW => POP_PAGEMODULEGROUP_PAGESECTION_QUICKVIEWFRAMETOPOPTIONS,
                    );
                    if ($frameoptions_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties($groups[$component[1]] ?? null)) {
                        $ret[] = $frameoptions_component;
                    }
                }
                break;

            case self::COMPONENT_PAGE_BODYSIDEINFO:
            case self::COMPONENT_PAGE_QUICKVIEWSIDEINFO:
                // If there is a sideinfo module then add the options module
                if ($sideinfocontent_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT)) {
                    // If the added sideinfo module is the empty one, then no need for the frame
                    if ($sideinfocontent_component != [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_EMPTYSIDEINFO]) {
                        if ($sideinfoframeoptions_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOFRAMEOPTIONS)) {
                            $ret[] = $sideinfoframeoptions_component;
                        }
                    }
                }
        }

        return $ret;
    }

    public function getFramebottomoptionsSubmodules(array $component): array
    {
        $ret = parent::getFramebottomoptionsSubmodules($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_PAGE_BODY:
            case self::COMPONENT_PAGE_QUICKVIEW:
                if ($pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT)) {
                     // Add only if the corresponding content module exists
                    $groups = array(
                        self::COMPONENT_PAGE_BODY => POP_PAGEMODULEGROUP_PAGESECTION_BODYFRAMEBOTTOMOPTIONS,
                        self::COMPONENT_PAGE_QUICKVIEW => POP_PAGEMODULEGROUP_PAGESECTION_QUICKVIEWFRAMEBOTTOMOPTIONS,
                    );
                    if ($frameoptions_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties($groups[$component[1]] ?? null)) {
                        $ret[] = $frameoptions_component;
                    }
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_PAGE_ADDONS:
                $this->appendProp($component, $props, 'class', 'tab-content');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
         // Make the frame components have a unique ID
            case self::COMPONENT_PAGE_FRAMECOMPONENTS:
                foreach (RequestUtils::getFramecomponentComponents() as $subComponent) {
                    $this->setProp([$subComponent], $props, 'unique-frontend-id', true);
                }
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}



