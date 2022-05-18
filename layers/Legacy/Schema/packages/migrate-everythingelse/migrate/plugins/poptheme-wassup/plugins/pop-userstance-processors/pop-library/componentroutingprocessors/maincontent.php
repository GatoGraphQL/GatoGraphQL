<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class UserStance_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $module];
        }

        // Default
        $routemodules = array(
            POP_USERSTANCE_ROUTE_ADDSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_STANCE_CREATE],
            POP_USERSTANCE_ROUTE_EDITSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_STANCE_UPDATE],
            POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_STANCE_CREATEORUPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
        }

        $default_format_votes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_STANCES);
        $default_format_myvotes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_MYSTANCES);

        $routemodules_typeahead = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }
        }

        $routemodules_navigator = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_SCROLL_NAVIGATOR],
        );
        foreach ($routemodules_navigator as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        $routemodules_addons = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_SCROLL_ADDONS],
        );
        foreach ($routemodules_addons as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }
        }

        $routemodules_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_mycontent = array(
            POP_USERSTANCE_ROUTE_MYSTANCES => [UserStance_Module_Processor_MySectionBlocks::class, UserStance_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYSTANCES_TABLE_EDIT],
        );
        foreach ($routemodules_mycontent as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_myvotes == POP_FORMAT_TABLE) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_mycontent_previews = array(
            POP_USERSTANCE_ROUTE_MYSTANCES => [UserStance_Module_Processor_MySectionBlocks::class, UserStance_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_previews as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_myvotes == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
            }
        }

        // Author route modules
        $default_format_authorvotes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_AUTHORSTANCES);

        $routemodules_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_carousels = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSTANCES_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_CAROUSEL) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $module];
            }
        }

        // Tag route modules
        $default_format_votes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_TAGSTANCES);

        $routemodules_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_carousels = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSTANCES_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_CAROUSEL) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $module];
            }
        }

        // Single route modules
        $default_format_singlevotes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_SINGLESTANCES);

        $routemodules_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singlevotes == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singlevotes == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $module];
            }
        }
        $routemodules_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singlevotes == POP_FORMAT_LIST) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $module];
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
		new UserStance_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
