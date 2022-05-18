<?php

class PoP_LocationPostsCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_LOCATIONPOST_CREATE = 'buttoninner-locationpost-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_LOCATIONPOST_CREATE],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        if (defined('POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST') && POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST) {
            $routes = array(
                self::COMPONENT_BUTTONINNER_LOCATIONPOST_CREATE => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
            );
            if ($route = $routes[$component[1]] ?? null) {
                return 'fa-fw '.getRouteIcon($route, false);
            }
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        $titles = array(
            self::COMPONENT_BUTTONINNER_LOCATIONPOST_CREATE => PoP_LocationPosts_PostNameUtils::getNameUc(),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }
}


