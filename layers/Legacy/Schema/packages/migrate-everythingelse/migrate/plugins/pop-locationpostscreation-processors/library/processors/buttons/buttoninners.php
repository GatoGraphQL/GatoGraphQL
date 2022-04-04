<?php

class PoP_LocationPostsCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_LOCATIONPOST_CREATE = 'buttoninner-locationpost-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_LOCATIONPOST_CREATE],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        if (defined('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST') && POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST) {
            $routes = array(
                self::MODULE_BUTTONINNER_LOCATIONPOST_CREATE => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
            );
            if ($route = $routes[$module[1]] ?? null) {
                return 'fa-fw '.getRouteIcon($route, false);
            }
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        $titles = array(
            self::MODULE_BUTTONINNER_LOCATIONPOST_CREATE => PoP_LocationPosts_PostNameUtils::getNameUc(),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($module);
    }
}


