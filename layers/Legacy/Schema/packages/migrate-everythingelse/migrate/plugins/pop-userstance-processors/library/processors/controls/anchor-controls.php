<?php

use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class UserStance_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT = 'buttoncontrol-stance-pro-generalcount';
    public final const MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT = 'buttoncontrol-stance-neutral-generalcount';
    public final const MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT = 'buttoncontrol-stance-against-generalcount';
    public final const MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT = 'buttoncontrol-stance-pro-articlecount';
    public final const MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT = 'buttoncontrol-stance-neutral-articlecount';
    public final const MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT = 'buttoncontrol-stance-against-articlecount';
    public final const MODULE_ANCHORCONTROL_STANCE_PRO_COUNT = 'buttoncontrol-stance-pro-count';
    public final const MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT = 'buttoncontrol-stance-neutral-count';
    public final const MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT = 'buttoncontrol-stance-against-count';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT],
            [self::class, self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT],
        );
    }

    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getTarget($module, $props);
    }

    public function getMutableonrequestText(array $module, array &$props)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT:
                $pro = TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance-processors');
                $neutral = TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance-processors');
                $against = TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance-processors');

                $labels = array(
                    self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => $pro,
                    self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => $neutral,
                    self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => $against,
                    self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => $pro,
                    self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => $neutral,
                    self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => $against,
                    self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT => $pro,
                    self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => $neutral,
                    self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT => $against,
                );
                $label = $labels[$module[1]];
                $cats = array(
                    self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => POP_USERSTANCE_TERM_STANCE_AGAINST,
                    self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => POP_USERSTANCE_TERM_STANCE_AGAINST,
                    self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT => POP_USERSTANCE_TERM_STANCE_AGAINST,
                );

                $general = array(
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT],
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT],
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT],
                );
                $article = array(
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT],
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT],
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT],
                );
                $combined = array(
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT],
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT],
                    [self::class, self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT],
                );

                $query = array();

                if (in_array($module, $general)) {
                      // Query all the General thoughts about TPP: add query args
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsGeneralstances($query);
                } elseif (in_array($module, $article)) {
                     // Query all the General thoughts about TPP: add query args
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsPoststances($query);
                } elseif (in_array($module, $combined)) {
                    $query['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];
                }

                // Override the category
                $query['tax-query'][] = [
                    'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                    'terms'    => $cats[$module[1]],
                ];

                // // All results
                // $query['limit'] = 0;
                // $query['fields'] = 'ids';

                $count = $customPostTypeAPI->getCustomPostCount($query);

                return sprintf(
                    '<strong>%s</strong> %s',
                    $count,
                    $label
                );
        }

        return parent::getMutableonrequestText($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        $routes = array(
            self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
        );
        if ($route = $routes[$module[1]] ?? null) {
            return getRouteIcon($route, false);
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $routes = array(
            self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
        );
        if ($route = $routes[$module[1]] ?? null) {
            return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_PRO_COUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_NEUTRAL_COUNT:
            case self::MODULE_ANCHORCONTROL_STANCE_AGAINST_COUNT:
                $this->appendProp($module, $props, 'class', 'btn btn-link');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


