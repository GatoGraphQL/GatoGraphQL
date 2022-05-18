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
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGE_QUICKVIEW],
            [self::class, self::MODULE_PAGE_QUICKVIEWSIDEINFO],
            [self::class, self::MODULE_PAGE_ADDONS],
            [self::class, self::MODULE_PAGE_BACKGROUND],
            [self::class, self::MODULE_PAGE_FRAMECOMPONENTS],
            [self::class, self::MODULE_PAGE_HOLE],
            [self::class, self::MODULE_PAGE_HOVER],
            [self::class, self::MODULE_PAGE_NAVIGATOR],
            [self::class, self::MODULE_PAGE_MODALS],
            [self::class, self::MODULE_PAGE_SIDE],
            [self::class, self::MODULE_PAGE_TOP],
            [self::class, self::MODULE_PAGE_BODYSIDEINFO],
            [self::class, self::MODULE_PAGE_BODY],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_PAGE_ADDONS:
            case self::MODULE_PAGE_QUICKVIEW:
            case self::MODULE_PAGE_QUICKVIEWSIDEINFO:
            case self::MODULE_PAGE_BACKGROUND:
            case self::MODULE_PAGE_HOLE:
            case self::MODULE_PAGE_HOVER:
            case self::MODULE_PAGE_NAVIGATOR:
            case self::MODULE_PAGE_MODALS:
            case self::MODULE_PAGE_SIDE:
            case self::MODULE_PAGE_TOP:
            case self::MODULE_PAGE_BODYSIDEINFO:
            case self::MODULE_PAGE_BODY:
                $module_groups = array(
                    self::MODULE_PAGE_ADDONS => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::MODULE_PAGE_QUICKVIEW => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::MODULE_PAGE_QUICKVIEWSIDEINFO => POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT,
                    self::MODULE_PAGE_BACKGROUND => POP_PAGEMODULEGROUP_PAGESECTION_BACKGROUNDFRAMECONTENT,
                    self::MODULE_PAGE_HOLE => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::MODULE_PAGE_HOVER => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::MODULE_PAGE_NAVIGATOR => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::MODULE_PAGE_MODALS => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                    self::MODULE_PAGE_SIDE => POP_PAGEMODULEGROUP_PAGESECTION_SIDEFRAMECONTENT,
                    self::MODULE_PAGE_TOP => POP_PAGEMODULEGROUP_PAGESECTION_TOPFRAMECONTENT,
                    self::MODULE_PAGE_BODYSIDEINFO => POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT,
                    self::MODULE_PAGE_BODY => POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT,
                );
                if ($page_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties($module_groups[$module[1]] ?? null)) {
                    $ret[] = $page_module;
                }
                break;
        }

        switch ($module[1]) {
            case self::MODULE_PAGE_FRAMECOMPONENTS:
                $moduleAtts = count($module) >= 3 ? $module[2] : null;
                if (!$moduleAtts || !$moduleAtts['onlyinitial']) {
                     // Load targeted module
                    if ($page_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT)) {
                        $ret[] = $page_module;
                    }
                }

                // Load initial frame components
                $ret = array_merge(
                    $ret,
                    RequestUtils::getFramecomponentComponentVariations()
                );
                break;
        }

        return $ret;
    }

    public function getFrametopoptionsSubmodules(array $module): array
    {
        $ret = parent::getFrametopoptionsSubmodules($module);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_PAGE_BODY:
            case self::MODULE_PAGE_QUICKVIEW:
                if ($pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT)) {
                     // Add only if the corresponding content module exists
                    $groups = array(
                        self::MODULE_PAGE_BODY => POP_PAGEMODULEGROUP_PAGESECTION_BODYFRAMETOPOPTIONS,
                        self::MODULE_PAGE_QUICKVIEW => POP_PAGEMODULEGROUP_PAGESECTION_QUICKVIEWFRAMETOPOPTIONS,
                    );
                    if ($frameoptions_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties($groups[$module[1]] ?? null)) {
                        $ret[] = $frameoptions_module;
                    }
                }
                break;

            case self::MODULE_PAGE_BODYSIDEINFO:
            case self::MODULE_PAGE_QUICKVIEWSIDEINFO:
                // If there is a sideinfo module then add the options module
                if ($sideinfocontent_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT)) {
                    // If the added sideinfo module is the empty one, then no need for the frame
                    if ($sideinfocontent_module != [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_EMPTYSIDEINFO]) {
                        if ($sideinfoframeoptions_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOFRAMEOPTIONS)) {
                            $ret[] = $sideinfoframeoptions_module;
                        }
                    }
                }
        }

        return $ret;
    }

    public function getFramebottomoptionsSubmodules(array $module): array
    {
        $ret = parent::getFramebottomoptionsSubmodules($module);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_PAGE_BODY:
            case self::MODULE_PAGE_QUICKVIEW:
                if ($pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT)) {
                     // Add only if the corresponding content module exists
                    $groups = array(
                        self::MODULE_PAGE_BODY => POP_PAGEMODULEGROUP_PAGESECTION_BODYFRAMEBOTTOMOPTIONS,
                        self::MODULE_PAGE_QUICKVIEW => POP_PAGEMODULEGROUP_PAGESECTION_QUICKVIEWFRAMEBOTTOMOPTIONS,
                    );
                    if ($frameoptions_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties($groups[$module[1]] ?? null)) {
                        $ret[] = $frameoptions_module;
                    }
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_PAGE_ADDONS:
                $this->appendProp($module, $props, 'class', 'tab-content');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
         // Make the frame components have a unique ID
            case self::MODULE_PAGE_FRAMECOMPONENTS:
                foreach (RequestUtils::getFramecomponentComponentVariations() as $submodule) {
                    $this->setProp([$submodule], $props, 'unique-frontend-id', true);
                }
                break;
        }

        parent::initWebPlatformModelProps($module, $props);
    }
}



