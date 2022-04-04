<?php
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_URE_Engine_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'ApplicationState:addVars',
            $this->addVars(...),
            10,
            1
        );
        \PoP\Root\App::addAction(
            'augmentVarsProperties',
            $this->augmentVarsProperties(...),
            10,
            1
        );
    }
    /**
     * @todo Migrate to AppStateProvider
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        if ($vars['nature'] == UserRequestNature::USER) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                $source = \PoP\Root\App::query(GD_URLPARAM_URECONTENTSOURCE);
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
     * @todo Migrate to AppStateProvider
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        if ($vars['nature'] == UserRequestNature::USER) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            $vars['routing']['queried-object-is-community'] = gdUreIsCommunity($author);
        }
    }
}

/**
 * Initialization
 */
new PoP_URE_Engine_Hooks();
