<?php
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPThemeWassup_UserCommunities_ResourceLoader_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoPThemeWassup_ResourceLoader_Hooks:extra-vars',
            array($this, 'getAuthorResourcesExtraVars'),
            10,
            3
        );
    }

    public function getAuthorResourcesExtraVars($extra_vars, $nature, $ids)
    {
        if ($nature == UserRequestNature::USER) {
            // Organization: it must add together the resources for both "source=community" and "source=user"
            // Then, for the organization and community roles, we must set the extra \PoP\Root\App::getState('source') value
            $source = array();
            foreach ($ids as $author_id) {
                // We only set-up the value for GD_URLPARAM_URECONTENTSOURCE_COMMUNITY.
                // The opposite value, for GD_URLPARAM_URECONTENTSOURCE_USER, is not needed, because:
                // 1. It is a subset from GD_URLPARAM_URECONTENTSOURCE_COMMUNITY, then its resources are already included there
                // 2. Its configuration is the same as when selecting non-community profiles (eg: Organization), which will also be tackled on separately
                if (gdUreIsCommunity($author_id)) {
                    $source[$author_id] = GD_URLPARAM_URECONTENTSOURCE_COMMUNITY;
                }
            }
            if ($source) {
                $extra_vars['source'] = $source;
            }
        }

        return $extra_vars;
    }
}
    
/**
 * Initialize
 */
new PoPThemeWassup_UserCommunities_ResourceLoader_Hooks();
