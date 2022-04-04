<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS = 'buttoncontrol-notifications';
    public final const MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD = 'buttoncontrol-notifications-markallasread';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS],
            [self::class, self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
                return TranslationAPIFacade::getInstance()->__('View all', 'pop-notifications-processors');

            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                return TranslationAPIFacade::getInstance()->__('Mark all as read', 'pop-notifications-processors');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
                return getRouteIcon(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS, false);

            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                return getRouteIcon(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD, false);
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                $routes = array(
                    self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
                    self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
                );
                $route = $routes[$module[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS:
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                $this->appendProp($module, $props, 'class', 'btn btn-link btn-compact');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:
                // Only if the user is logged in on any one domain
                $this->appendProp($module, $props, 'class', 'visible-loggedin-anydomain');

                // Tell the Search engines to not follow the link
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'rel' => 'nofollow',
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
}


