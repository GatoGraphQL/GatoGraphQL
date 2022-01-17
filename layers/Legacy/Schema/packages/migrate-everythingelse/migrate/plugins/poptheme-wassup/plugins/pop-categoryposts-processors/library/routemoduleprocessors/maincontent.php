<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_CategoryPostsProcessors_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Page modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_typeahead = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_navigator = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_NAVIGATOR],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_NAVIGATOR],
        );
        foreach ($routemodules_navigator as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_section == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        $routemodules_addons = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_ADDONS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_ADDONS],
        );
        foreach ($routemodules_addons as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_details = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_line = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS10_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS11_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_SCROLL_LINE],
        );
        foreach ($routemodules_line as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LINE,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LINE) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_carousels = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSEL) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_carousels = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS02_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS03_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS04_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS05_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS06_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS07_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS08_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS09_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS00_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS01_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS12_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS13_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS14_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS15_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS16_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS17_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS18_CAROUSEL_CONTENT],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_CATEGORYPOSTS19_CAROUSEL_CONTENT],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSELCONTENT,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSELCONTENT) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        // Author route modules
        $default_format_authorsection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTION);

        $routemodules_details = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS00_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS01_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS02_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS03_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS04_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS05_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS06_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS07_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS08_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS09_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS10_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS11_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS12_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS13_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS14_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS15_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS16_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS17_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS18_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS19_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_line = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS00_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS01_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS02_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS03_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS04_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS05_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS06_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS07_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS08_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS09_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS10_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS11_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS12_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS13_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS14_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS15_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS16_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS17_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS18_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS19_SCROLL_LINE],
        );
        foreach ($routemodules_line as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LINE,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_LINE) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_carousels = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS00_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS01_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS02_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS03_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS04_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS05_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS06_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS07_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS08_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS09_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS10_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS11_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS12_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS13_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS14_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS15_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS16_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS17_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS18_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORCATEGORYPOSTS19_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_CAROUSEL) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }

        // Tag route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);

        $routemodules_details = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS00_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS01_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS02_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS03_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS04_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS05_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS06_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS07_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS08_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS09_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS10_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS11_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS12_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS13_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS14_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS15_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS16_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS17_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS18_SCROLL_DETAILS],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS19_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS00_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS01_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS02_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS03_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS04_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS05_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS06_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS07_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS08_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS09_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS10_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS11_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS12_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS13_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS14_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS15_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS16_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS17_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS18_SCROLL_LIST],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS19_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_line = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS00_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS01_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS02_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS03_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS04_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS05_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS06_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS07_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS08_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS09_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS10_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS11_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS12_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS13_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS14_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS15_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS16_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS17_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS18_SCROLL_LINE],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS19_SCROLL_LINE],
        );
        foreach ($routemodules_line as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LINE,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LINE) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }


        $routemodules_carousels = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS00_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS01_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS02_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS03_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS04_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS05_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS06_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS07_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS08_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS09_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS10_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS11_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS12_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS13_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS14_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS15_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS16_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS17_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS18_CAROUSEL],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionBlocks::class, CPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGCATEGORYPOSTS19_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSEL) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_CategoryPostsProcessors_Module_MainContentRouteModuleProcessor()
	);
}, 200);
