<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_NoSearchCategoryPostsProcessors_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_typeahead = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS00_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS01_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS02_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS03_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS04_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS05_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS06_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS07_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS08_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS09_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS10_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS11_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS12_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS13_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS14_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS15_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS16_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS17_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS18_TYPEAHEAD],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_NOSEARCHCATEGORYPOSTS19_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }

        $routemodules_navigator = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_NAVIGATOR],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_NAVIGATOR],
        );
        foreach ($routemodules_navigator as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_section == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        $routemodules_addons = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_ADDONS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_ADDONS],
        );
        foreach ($routemodules_addons as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }

        $routemodules_details = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_simpleview = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_fullview = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_thumbnail = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_list = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_line = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS10_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS11_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_SCROLL_LINE],
        );
        foreach ($routemodules_line as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LINE,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LINE) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_carousels = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSEL) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_carousels = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS02_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS03_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS04_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS05_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS06_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS07_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS08_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS09_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS00_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS01_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS12_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS13_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS14_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS15_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS16_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS17_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS18_CAROUSEL_CONTENT],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_NOSEARCHCATEGORYPOSTS19_CAROUSEL_CONTENT],
        );
        foreach ($routemodules_carousels as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSELCONTENT,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSELCONTENT) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }

        // Author route modules
        $default_format_authorsection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTION);

        $routemodules_details = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_simpleview = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_fullview = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_thumbnail = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_list = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_line = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_LINE],
        );
        foreach ($routemodules_line as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LINE,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_LINE) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_carousels = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS00_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS01_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS02_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS03_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS04_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS05_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS06_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS07_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS08_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS09_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS10_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS11_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS12_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS13_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS14_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS15_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS16_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS17_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS18_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_AUTHORNOSEARCHCATEGORYPOSTS19_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_CAROUSEL) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }

        // Tag route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);

        $routemodules_details = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_simpleview = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_fullview = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_thumbnail = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_list = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_line = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_LINE],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_LINE],
        );
        foreach ($routemodules_line as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LINE,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LINE) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }


        $routemodules_carousels = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS00_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS01_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS02_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS03_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS04_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS05_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS06_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS07_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS08_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS09_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS10_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS11_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS12_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS13_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS14_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS15_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS16_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS17_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS18_CAROUSEL],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionBlocks::class, NSCPP_Module_Processor_SectionBlocks::MODULE_BLOCK_TAGNOSEARCHCATEGORYPOSTS19_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_CAROUSEL) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_NoSearchCategoryPostsProcessors_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
