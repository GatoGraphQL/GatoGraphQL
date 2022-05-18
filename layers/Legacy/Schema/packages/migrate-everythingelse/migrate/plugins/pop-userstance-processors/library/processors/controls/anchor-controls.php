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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT],
            [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT],
        );
    }

    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getTarget($component, $props);
    }

    public function getMutableonrequestText(array $component, array &$props)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT:
                $pro = TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance-processors');
                $neutral = TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance-processors');
                $against = TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance-processors');

                $labels = array(
                    self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => $pro,
                    self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => $neutral,
                    self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => $against,
                    self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => $pro,
                    self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => $neutral,
                    self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => $against,
                    self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT => $pro,
                    self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => $neutral,
                    self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT => $against,
                );
                $label = $labels[$component[1]];
                $cats = array(
                    self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => POP_USERSTANCE_TERM_STANCE_AGAINST,
                    self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => POP_USERSTANCE_TERM_STANCE_AGAINST,
                    self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT => POP_USERSTANCE_TERM_STANCE_PRO,
                    self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT => POP_USERSTANCE_TERM_STANCE_AGAINST,
                );

                $general = array(
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT],
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT],
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT],
                );
                $article = array(
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT],
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT],
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT],
                );
                $combined = array(
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT],
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT],
                    [self::class, self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT],
                );

                $query = array();

                if (in_array($component, $general)) {
                      // Query all the General thoughts about TPP: add query args
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsGeneralstances($query);
                } elseif (in_array($component, $article)) {
                     // Query all the General thoughts about TPP: add query args
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsPoststances($query);
                } elseif (in_array($component, $combined)) {
                    $query['custompost-types'] = [POP_USERSTANCE_POSTTYPE_USERSTANCE];
                }

                // Override the category
                $query['tax-query'][] = [
                    'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                    'terms'    => $cats[$component[1]],
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

        return parent::getMutableonrequestText($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
        );
        if ($route = $routes[$component[1]] ?? null) {
            return getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $routes = array(
            self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
            self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
            self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
            self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
        );
        if ($route = $routes[$component[1]] ?? null) {
            return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($component, $props);
    }
    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_GENERALCOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_ARTICLECOUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_PRO_COUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_NEUTRAL_COUNT:
            case self::COMPONENT_ANCHORCONTROL_STANCE_AGAINST_COUNT:
                $this->appendProp($component, $props, 'class', 'btn btn-link');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


