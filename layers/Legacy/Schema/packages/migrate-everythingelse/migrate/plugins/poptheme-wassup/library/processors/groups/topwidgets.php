<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CustomGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_GROUP_HOMETOP = 'group-hometop';
    public final const COMPONENT_GROUP_HOME_WELCOME = 'group-home-welcome';
    public final const COMPONENT_GROUP_HOME_COMPACTWELCOME = 'group-home-compactwelcome';
    public final const COMPONENT_GROUP_HOME_WIDGETAREA = 'group-home-widgetarea';
    public final const COMPONENT_GROUP_HOME_WELCOMEACCOUNT = 'group-home-welcomeaccount';
    public final const COMPONENT_GROUP_AUTHORTOP = 'group-authortop';
    public final const COMPONENT_GROUP_AUTHOR_DESCRIPTION = 'group-author-description';
    public final const COMPONENT_GROUP_AUTHOR_WIDGETAREA = 'group-author-widgetarea';
    public final const COMPONENT_GROUP_TAG_WIDGETAREA = 'group-tag-widgetarea';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_GROUP_HOMETOP],
            [self::class, self::COMPONENT_GROUP_HOME_WELCOME],
            [self::class, self::COMPONENT_GROUP_HOME_COMPACTWELCOME],
            [self::class, self::COMPONENT_GROUP_HOME_WIDGETAREA],
            [self::class, self::COMPONENT_GROUP_HOME_WELCOMEACCOUNT],
            [self::class, self::COMPONENT_GROUP_AUTHORTOP],
            [self::class, self::COMPONENT_GROUP_AUTHOR_DESCRIPTION],
            [self::class, self::COMPONENT_GROUP_AUTHOR_WIDGETAREA],
            [self::class, self::COMPONENT_GROUP_TAG_WIDGETAREA],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_GROUP_HOME_WELCOME:
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMEWELCOMETOP];
                $ret[] = [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_HOMEWELCOME];
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMEWELCOMEBOTTOM];
                $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME];
                $ret[] = [self::class, self::COMPONENT_GROUP_HOME_WELCOMEACCOUNT];
                break;

            case self::COMPONENT_GROUP_HOME_COMPACTWELCOME:
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMECOMPACTWELCOMETOP];
                $ret[] = [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_HOMEWELCOME];
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM];
                break;

            case self::COMPONENT_GROUP_HOME_WELCOMEACCOUNT:
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN];
                $ret[] = [PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::COMPONENT_BLOCK_NEWSLETTER];
                break;

            case self::COMPONENT_GROUP_AUTHOR_DESCRIPTION:
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_AUTHORDESCRIPTIONTOP];
                $ret[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT];
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_AUTHORDESCRIPTIONBOTTOM];
                break;

            case self::COMPONENT_GROUP_HOME_WIDGETAREA:
                // Add the blocks
                if ($components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomGroups:components:home_widgetarea',
                    array(),
                    $component
                )) {
                    $ret = array_merge(
                        $ret,
                        $components
                    );
                }
                break;

            case self::COMPONENT_GROUP_AUTHOR_WIDGETAREA:
                // Add the blocks
                if ($components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomGroups:components:author_widgetarea',
                    array(),
                    $component
                )) {
                    $ret = array_merge(
                        $ret,
                        $components
                    );
                }
                break;

            case self::COMPONENT_GROUP_TAG_WIDGETAREA:
                $components = array();
                $components[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_TAG_CONTENT];

                // Allow to add the Featured Carousel
                if ($components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomGroups:components:tag_widgetarea',
                    $components,
                    $component
                )) {
                    $ret = array_merge(
                        $ret,
                        $components
                    );
                }
                break;

            case self::COMPONENT_GROUP_HOMETOP:
                $ret[] = [self::class, self::COMPONENT_GROUP_HOME_COMPACTWELCOME];

                // Allow MESYM to override this
                if ($widgetarea = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomGroups:hometop:components:widget',
                    [self::class, self::COMPONENT_GROUP_HOME_WIDGETAREA]
                )) {
                    $ret[] = $widgetarea;
                }
                break;

            case self::COMPONENT_GROUP_AUTHORTOP:
                $ret[] = [self::class, self::COMPONENT_GROUP_AUTHOR_DESCRIPTION];

                // Allow MESYM to override this
                if ($widgetarea = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomGroups:authortop:components:widget',
                    [self::class, self::COMPONENT_GROUP_AUTHOR_WIDGETAREA]
                )) {
                    $ret[] = $widgetarea;
                }
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_GROUP_HOME_WELCOME:
            case self::COMPONENT_GROUP_HOME_COMPACTWELCOME:
                // It will add class "in" through .js if there is no cookie
                $this->addJsmethod($ret, 'cookieToggleClass');
                break;
        }

        return $ret;
    }

    public function initWebPlatformRequestProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_GROUP_HOME_WELCOME:
            case self::COMPONENT_GROUP_HOME_COMPACTWELCOME:
            case self::COMPONENT_GROUP_AUTHOR_DESCRIPTION:
                // Associate modules all together
                // First assign the frontend-id to the collapsible module
                $collapsible_subcomponents = array(
                    self::COMPONENT_GROUP_HOME_WELCOME => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_HOMEWELCOME],
                    self::COMPONENT_GROUP_HOME_COMPACTWELCOME => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_HOMEWELCOME],
                    self::COMPONENT_GROUP_AUTHOR_DESCRIPTION => [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT],
                );
                $frontend_id = $this->getFrontendId($component, $props);
                $collapsible_frontend_id = $frontend_id.'collapse';
                $this->setProp([$collapsible_subcomponents[$component[1]]], $props, 'frontend-id', $collapsible_frontend_id);

                // Then set the frontend-id to the labels
                $label_subcomponents_set = array(
                    self::COMPONENT_GROUP_HOME_WELCOME => array(
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMEWELCOMETOP],
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMEWELCOMEBOTTOM],
                    ),
                    self::COMPONENT_GROUP_HOME_COMPACTWELCOME => array(
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMECOMPACTWELCOMETOP],
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM],
                    ),
                    self::COMPONENT_GROUP_AUTHOR_DESCRIPTION => array(
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_AUTHORDESCRIPTIONTOP],
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::COMPONENT_HTMLCODE_AUTHORDESCRIPTIONBOTTOM],
                    ),
                );
                foreach ($label_subcomponents_set[$component[1]] as $subComponent) {
                    $this->setProp([$subComponent], $props, 'target-id', $collapsible_frontend_id);
                }
                break;
        }

        parent::initWebPlatformRequestProps($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_GROUP_HOME_WELCOMEACCOUNT:
                $this->appendProp($component, $props, 'class', 'row');
                break;

            case self::COMPONENT_GROUP_HOME_WELCOME:
            case self::COMPONENT_GROUP_HOME_COMPACTWELCOME:
                // Make it open by default, then the .js will take it out if there's a cookie
                $this->appendProp([[PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_HOMEWELCOME]], $props, 'class', 'collapse in');
                break;

            case self::COMPONENT_GROUP_AUTHOR_DESCRIPTION:
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT]], $props, 'class', 'collapse in row row-item');
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_GROUP_AUTHOR_DESCRIPTION:
                $this->appendProp($component, $props, 'class', 'blockgroup-author-description');
                break;

            case self::COMPONENT_GROUP_HOMETOP:
                $this->appendProp($component, $props, 'class', 'blockgroup-hometop');
                break;

            case self::COMPONENT_GROUP_AUTHORTOP:
                $this->appendProp($component, $props, 'class', 'blockgroup-authortop');
                break;

            case self::COMPONENT_GROUP_HOME_WELCOMEACCOUNT:
                // Do not show if the user is logged in
                // Notice that it works for the domain from wherever this block is being fetched from!
                $cmsService = CMSServiceFacade::getInstance();
                $this->appendProp($component, $props, 'class', 'visible-notloggedin-'.RequestUtils::getDomainId($cmsService->getSiteURL()));

                // Give it some formatting
                $this->appendProp($component, $props, 'class', 'well well-sm');
                break;

            case self::COMPONENT_GROUP_HOME_WELCOME:
            case self::COMPONENT_GROUP_HOME_COMPACTWELCOME:
                $this->appendProp($component, $props, 'class', 'blockgroup-home-welcome');

                // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                     // Set the params for the cookie: show the welcome message, until the user clicks on "Close"
                    $frontend_id = $this->getFrontendId($component, $props);
                    $target = '#'.$frontend_id.'>.blocksection-extensions';
                    $deletecookiebtn = '#'.$frontend_id.'-expand>a';
                    $setcookiebtn = '#'.$frontend_id.'-collapse>a';/*, #'.$frontend_id.'-collapsebottom>a';*/
                    $this->mergeProp(
                        $component,
                        $props,
                        'params',
                        array(
                            'data-cookieid' => $component,
                            'data-cookietarget' => $target,
                            'data-cookieclass' => 'in',
                            'data-deletecookiebtn' => $deletecookiebtn,
                            'data-setcookiebtn' => $setcookiebtn,
                            'data-initial' => 'toggle',
                        )
                    );
                }
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_GROUP_HOME_WIDGETAREA:
            case self::COMPONENT_GROUP_AUTHOR_WIDGETAREA:
            case self::COMPONENT_GROUP_TAG_WIDGETAREA:
                // External Injection
                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomGroups:components:props',
                    $component,
                    array(&$props),
                    $this
                );
                break;

            case self::COMPONENT_GROUP_HOME_WELCOMEACCOUNT:
                $this->appendProp([[PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN]], $props, 'class', 'col-md-6');
                $this->appendProp([[PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::COMPONENT_BLOCK_NEWSLETTER]], $props, 'class', 'col-md-6');
                $this->setProp(
                    [
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN],
                    ],
                    $props,
                    'description',
                    sprintf(
                        '<h3>%s</h3>',
                        TranslationAPIFacade::getInstance()->__('Log in or Register', 'poptheme-wassup')
                    )
                );
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_GROUP_HOME_WELCOME:
            case self::COMPONENT_GROUP_HOME_COMPACTWELCOME:
                $this->appendProp([[PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_HOMEWELCOME]], $props, 'class', 'jumbotron text-center');
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_GROUP_HOME_WELCOME:
                $this->appendProp([[PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME]], $props, 'class', 'well well-sm');
                break;

            case self::COMPONENT_GROUP_AUTHOR_DESCRIPTION:
                // No title on the Description block
                $this->setProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT]], $props, 'title', '');
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT]], $props, 'class', 'col-xs-12');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


