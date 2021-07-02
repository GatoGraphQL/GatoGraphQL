<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Module_Processor_CustomGroups extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_GROUP_HOMETOP = 'group-hometop';
    public const MODULE_GROUP_HOME_WELCOME = 'group-home-welcome';
    public const MODULE_GROUP_HOME_COMPACTWELCOME = 'group-home-compactwelcome';
    public const MODULE_GROUP_HOME_WIDGETAREA = 'group-home-widgetarea';
    public const MODULE_GROUP_HOME_WELCOMEACCOUNT = 'group-home-welcomeaccount';
    public const MODULE_GROUP_AUTHORTOP = 'group-authortop';
    public const MODULE_GROUP_AUTHOR_DESCRIPTION = 'group-author-description';
    public const MODULE_GROUP_AUTHOR_WIDGETAREA = 'group-author-widgetarea';
    public const MODULE_GROUP_TAG_WIDGETAREA = 'group-tag-widgetarea';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_HOMETOP],
            [self::class, self::MODULE_GROUP_HOME_WELCOME],
            [self::class, self::MODULE_GROUP_HOME_COMPACTWELCOME],
            [self::class, self::MODULE_GROUP_HOME_WIDGETAREA],
            [self::class, self::MODULE_GROUP_HOME_WELCOMEACCOUNT],
            [self::class, self::MODULE_GROUP_AUTHORTOP],
            [self::class, self::MODULE_GROUP_AUTHOR_DESCRIPTION],
            [self::class, self::MODULE_GROUP_AUTHOR_WIDGETAREA],
            [self::class, self::MODULE_GROUP_TAG_WIDGETAREA],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_GROUP_HOME_WELCOME:
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMEWELCOMETOP];
                $ret[] = [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME];
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM];
                $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::MODULE_USERACCOUNT_USERLOGGEDINWELCOME];
                $ret[] = [self::class, self::MODULE_GROUP_HOME_WELCOMEACCOUNT];
                break;

            case self::MODULE_GROUP_HOME_COMPACTWELCOME:
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP];
                $ret[] = [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME];
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM];
                break;

            case self::MODULE_GROUP_HOME_WELCOMEACCOUNT:
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN];
                $ret[] = [PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::MODULE_BLOCK_NEWSLETTER];
                break;

            case self::MODULE_GROUP_AUTHOR_DESCRIPTION:
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP];
                $ret[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT];
                $ret[] = [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM];
                break;

            case self::MODULE_GROUP_HOME_WIDGETAREA:
                // Add the blocks
                if ($modules = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomGroups:modules:home_widgetarea',
                    array(),
                    $module
                )) {
                    $ret = array_merge(
                        $ret,
                        $modules
                    );
                }
                break;

            case self::MODULE_GROUP_AUTHOR_WIDGETAREA:
                // Add the blocks
                if ($modules = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomGroups:modules:author_widgetarea',
                    array(),
                    $module
                )) {
                    $ret = array_merge(
                        $ret,
                        $modules
                    );
                }
                break;

            case self::MODULE_GROUP_TAG_WIDGETAREA:
                $modules = array();
                $modules[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_TAG_CONTENT];

                // Allow to add the Featured Carousel
                if ($modules = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomGroups:modules:tag_widgetarea',
                    $modules,
                    $module
                )) {
                    $ret = array_merge(
                        $ret,
                        $modules
                    );
                }
                break;

            case self::MODULE_GROUP_HOMETOP:
                $ret[] = [self::class, self::MODULE_GROUP_HOME_COMPACTWELCOME];

                // Allow MESYM to override this
                if ($widgetarea = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomGroups:hometop:modules:widget',
                    [self::class, self::MODULE_GROUP_HOME_WIDGETAREA]
                )) {
                    $ret[] = $widgetarea;
                }
                break;

            case self::MODULE_GROUP_AUTHORTOP:
                $ret[] = [self::class, self::MODULE_GROUP_AUTHOR_DESCRIPTION];

                // Allow MESYM to override this
                if ($widgetarea = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomGroups:authortop:modules:widget',
                    [self::class, self::MODULE_GROUP_AUTHOR_WIDGETAREA]
                )) {
                    $ret[] = $widgetarea;
                }
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_GROUP_HOME_WELCOME:
            case self::MODULE_GROUP_HOME_COMPACTWELCOME:
                // It will add class "in" through .js if there is no cookie
                $this->addJsmethod($ret, 'cookieToggleClass');
                break;
        }

        return $ret;
    }

    public function initWebPlatformRequestProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_GROUP_HOME_WELCOME:
            case self::MODULE_GROUP_HOME_COMPACTWELCOME:
            case self::MODULE_GROUP_AUTHOR_DESCRIPTION:
                // Associate modules all together
                // First assign the frontend-id to the collapsible module
                $collapsible_submodules = array(
                    self::MODULE_GROUP_HOME_WELCOME => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME],
                    self::MODULE_GROUP_HOME_COMPACTWELCOME => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME],
                    self::MODULE_GROUP_AUTHOR_DESCRIPTION => [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT],
                );
                $frontend_id = $this->getFrontendId($module, $props);
                $collapsible_frontend_id = $frontend_id.'collapse';
                $this->setProp([$collapsible_submodules[$module[1]]], $props, 'frontend-id', $collapsible_frontend_id);

                // Then set the frontend-id to the labels
                $label_submodules_set = array(
                    self::MODULE_GROUP_HOME_WELCOME => array(
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMEWELCOMETOP],
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM],
                    ),
                    self::MODULE_GROUP_HOME_COMPACTWELCOME => array(
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP],
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM],
                    ),
                    self::MODULE_GROUP_AUTHOR_DESCRIPTION => array(
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP],
                        [PoP_Module_Processor_HTMLCodes::class, PoP_Module_Processor_HTMLCodes::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM],
                    ),
                );
                foreach ($label_submodules_set[$module[1]] as $submodule) {
                    $this->setProp([$submodule], $props, 'target-id', $collapsible_frontend_id);
                }
                break;
        }

        parent::initWebPlatformRequestProps($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_GROUP_HOME_WELCOMEACCOUNT:
                $this->appendProp($module, $props, 'class', 'row');
                break;

            case self::MODULE_GROUP_HOME_WELCOME:
            case self::MODULE_GROUP_HOME_COMPACTWELCOME:
                // Make it open by default, then the .js will take it out if there's a cookie
                $this->appendProp([[PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME]], $props, 'class', 'collapse in');
                break;

            case self::MODULE_GROUP_AUTHOR_DESCRIPTION:
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT]], $props, 'class', 'collapse in row row-item');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_GROUP_AUTHOR_DESCRIPTION:
                $this->appendProp($module, $props, 'class', 'blockgroup-author-description');
                break;

            case self::MODULE_GROUP_HOMETOP:
                $this->appendProp($module, $props, 'class', 'blockgroup-hometop');
                break;

            case self::MODULE_GROUP_AUTHORTOP:
                $this->appendProp($module, $props, 'class', 'blockgroup-authortop');
                break;

            case self::MODULE_GROUP_HOME_WELCOMEACCOUNT:
                // Do not show if the user is logged in
                // Notice that it works for the domain from wherever this block is being fetched from!
                $cmsService = CMSServiceFacade::getInstance();
                $this->appendProp($module, $props, 'class', 'visible-notloggedin-'.RequestUtils::getDomainId($cmsService->getSiteURL()));

                // Give it some formatting
                $this->appendProp($module, $props, 'class', 'well well-sm');
                break;

            case self::MODULE_GROUP_HOME_WELCOME:
            case self::MODULE_GROUP_HOME_COMPACTWELCOME:
                $this->appendProp($module, $props, 'class', 'blockgroup-home-welcome');

                // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                     // Set the params for the cookie: show the welcome message, until the user clicks on "Close"
                    $frontend_id = $this->getFrontendId($module, $props);
                    $target = '#'.$frontend_id.'>.blocksection-extensions';
                    $deletecookiebtn = '#'.$frontend_id.'-expand>a';
                    $setcookiebtn = '#'.$frontend_id.'-collapse>a';/*, #'.$frontend_id.'-collapsebottom>a';*/
                    $this->mergeProp(
                        $module,
                        $props,
                        'params',
                        array(
                            'data-cookieid' => $module,
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

        switch ($module[1]) {
            case self::MODULE_GROUP_HOME_WIDGETAREA:
            case self::MODULE_GROUP_AUTHOR_WIDGETAREA:
            case self::MODULE_GROUP_TAG_WIDGETAREA:
                // External Injection
                HooksAPIFacade::getInstance()->doAction(
                    'PoP_Module_Processor_CustomGroups:modules:props',
                    $module,
                    array(&$props),
                    $this
                );
                break;

            case self::MODULE_GROUP_HOME_WELCOMEACCOUNT:
                $this->appendProp([[PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN]], $props, 'class', 'col-md-6');
                $this->appendProp([[PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::MODULE_BLOCK_NEWSLETTER]], $props, 'class', 'col-md-6');
                $this->setProp(
                    [
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN],
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

        switch ($module[1]) {
            case self::MODULE_GROUP_HOME_WELCOME:
            case self::MODULE_GROUP_HOME_COMPACTWELCOME:
                $this->appendProp([[PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME]], $props, 'class', 'jumbotron text-center');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_GROUP_HOME_WELCOME:
                $this->appendProp([[PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::MODULE_USERACCOUNT_USERLOGGEDINWELCOME]], $props, 'class', 'well well-sm');
                break;

            case self::MODULE_GROUP_AUTHOR_DESCRIPTION:
                // No title on the Description block
                $this->setProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT]], $props, 'title', '');
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT]], $props, 'class', 'col-xs-12');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


