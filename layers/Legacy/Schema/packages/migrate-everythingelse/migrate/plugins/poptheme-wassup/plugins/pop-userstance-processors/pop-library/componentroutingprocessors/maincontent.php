<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class UserStance_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
        }

        // Default
        $routeComponents = array(
            POP_USERSTANCE_ROUTE_ADDSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_STANCE_CREATE],
            POP_USERSTANCE_ROUTE_EDITSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_STANCE_UPDATE],
            POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_STANCE_CREATEORUPDATE],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_votes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_STANCES);
        $default_format_myvotes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_MYSTANCES);

        $routeComponents_typeahead = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_TYPEAHEAD],
        );
        foreach ($routeComponents_typeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_navigator = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_SCROLL_NAVIGATOR],
        );
        foreach ($routeComponents_navigator as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        $routeComponents_addons = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_SCROLL_ADDONS],
        );
        foreach ($routeComponents_addons as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_GENERAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_GENERAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_PRO_ARTICLE_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_mycontent = array(
            POP_USERSTANCE_ROUTE_MYSTANCES => [UserStance_Module_Processor_MySectionBlocks::class, UserStance_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYSTANCES_TABLE_EDIT],
        );
        foreach ($routeComponents_mycontent as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_myvotes == POP_FORMAT_TABLE) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_mycontent_previews = array(
            POP_USERSTANCE_ROUTE_MYSTANCES => [UserStance_Module_Processor_MySectionBlocks::class, UserStance_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routeComponents_mycontent_previews as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_myvotes == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_authorvotes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_AUTHORSTANCES);

        $routeComponents_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_AGAINST_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_carousels = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL],
        );
        foreach ($routeComponents_carousels as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_authorvotes == POP_FORMAT_CAROUSEL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }

        // Tag route modules
        $default_format_votes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_TAGSTANCES);

        $routeComponents_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_NEUTRAL_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_AGAINST_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_carousels = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSTANCES_CAROUSEL],
        );
        foreach ($routeComponents_carousels as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_votes == POP_FORMAT_CAROUSEL) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }

        // Single route modules
        $default_format_singlevotes = PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_SINGLESTANCES);

        $routeComponents_fullview = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singlevotes == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singlevotes == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_USERSTANCE_ROUTE_STANCES => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_PRO => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_AGAINST => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
            POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singlevotes == POP_FORMAT_LIST) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
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
