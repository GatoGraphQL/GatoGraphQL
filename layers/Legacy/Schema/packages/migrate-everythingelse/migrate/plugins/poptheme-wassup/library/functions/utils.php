<?php
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPThemeWassup_Utils
{
    protected static $checkLoadingPagesectionModule;
    public static function checkLoadingPagesectionModule()
    {
        if (is_null(self::$checkLoadingPagesectionModule)) {
            $vars = ApplicationState::getVars();
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var ModulePaths */
            $modulePaths = $instanceManager->getInstance(ModulePaths::class);

            // If we are targeting specific module paths, then no need to validate. Otherwise, we must check that the module is under only 1 pageSection, or it may be repeated here and there
            self::$checkLoadingPagesectionModule = HooksAPIFacade::getInstance()->applyFilters(
                'PoPThemeWassup_Utils:checkLoadingPagesectionModule',
                $vars['modulefilter'] !== $modulePaths->getName()
            );
        }

        return self::$checkLoadingPagesectionModule;
    }

    public static function getFrontendId($frontend_id, $group)
    {
        // As defined in helper generateId in helpers.handlebars.js
        return $frontend_id . POP_CONSTANT_ID_SEPARATOR . $group;
    }

    // Return all the classes to make the pageSections active inside the pageSectionGroup
    // Needed to display the html immediately when doing serverside-rendering, so no need to wait
    // for javascript to execute to have content visible
    public static function getPagesectiongroupActivePagesectionClasses($active_classes = array())
    {
        // If PoP SSR is not defined, then there is no PoP_SSR_ServerUtils
        if (defined('POP_SSR_INITIALIZED')) {
            if (!PoP_SSR_ServerUtils::disableServerSideRendering()) {
                $engine = EngineFacade::getInstance();
                $data = $engine->data;
                $configuration = $data['modulesettings']['combinedstate']['configuration'];
                // Because the pageSection names may be mangled (so that "body" will be "x3" or something like that),
                // repeat the name of the pageSection/class below
                $possiblyOpenPageSections = array(
                    PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODY => 'body',
                    PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODYSIDEINFO => 'sideinfo',
                    PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_HOVER => 'hover',
                );
                foreach ($possiblyOpenPageSections as $possiblyOpenPageSection => $class) {
                    $pageSectionBlocks = arrayFlatten(array_values($configuration[$possiblyOpenPageSection][GD_JS_SUBMODULEOUTPUTNAMES] ?? []));
                    if ($pageSectionBlocks) {
                        // If the pageSection is sideinfo, open it as long as the block is not the EMPTYBLOCK
                        if ($possiblyOpenPageSection == PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODYSIDEINFO) {
                            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                            $emptysideinfoModuleOutputName = ModuleUtils::getModuleOutputName([PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_EMPTYSIDEINFO]);
                            if (in_array($emptysideinfoModuleOutputName, $pageSectionBlocks)) {
                                continue;
                            }
                        }
                        $active_classes[] = 'active-'.$class;
                    }
                }
            }
        }

        // Hook: allow Verticals to remove 'active-side' class
        $active_classes = HooksAPIFacade::getInstance()->applyFilters(
            'PoP_ApplicationProcessors_Utils:pagesectiongroup:active_pagesection_classes',
            $active_classes
        );

        return $active_classes;
    }
}
