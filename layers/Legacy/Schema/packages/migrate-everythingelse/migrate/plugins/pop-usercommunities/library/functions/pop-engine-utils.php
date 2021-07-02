<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoP_URE_Engine_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'ApplicationState:addVars',
            [$this, 'addVars'],
            10,
            1
        );
        HooksAPIFacade::getInstance()->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
        );
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        if ($vars['nature'] == UserRouteNatures::USER) {
            $author = $vars['routing-state']['queried-object-id'];
            if (gdUreIsCommunity($author)) {
                $source = $_REQUEST[GD_URLPARAM_URECONTENTSOURCE] ?? null;
                $sources = array(
                    GD_URLPARAM_URECONTENTSOURCE_USER,
                    GD_URLPARAM_URECONTENTSOURCE_COMMUNITY,
                );
                if (!in_array($source, $sources)) {
                    $source = gdUreGetDefaultContentsource();
                }

                $vars['source'] = $source;
            }
        }
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        if ($vars['nature'] == UserRouteNatures::USER) {
            $author = $vars['routing-state']['queried-object-id'];
            $vars['routing-state']['queried-object-is-community'] = gdUreIsCommunity($author);
        }
    }
}

/**
 * Initialization
 */
new PoP_URE_Engine_Hooks();
