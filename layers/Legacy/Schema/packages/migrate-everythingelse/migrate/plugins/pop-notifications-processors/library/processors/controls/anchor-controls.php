<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS = 'buttoncontrol-notifications';
    public final const MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD = 'buttoncontrol-notifications-markallasread';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS],
            [self::class, self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
                return TranslationAPIFacade::getInstance()->__('View all', 'pop-notifications-processors');

            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                return TranslationAPIFacade::getInstance()->__('Mark all as read', 'pop-notifications-processors');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
                return getRouteIcon(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS, false);

            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                return getRouteIcon(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD, false);
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                $routes = array(
                    self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
                    self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
                );
                $route = $routes[$componentVariation[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($componentVariation, $props);
    }
    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-link btn-compact');
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                // Only if the user is logged in on any one domain
                $this->appendProp($componentVariation, $props, 'class', 'visible-loggedin-anydomain');

                // Tell the Search engines to not follow the link
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'rel' => 'nofollow',
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


