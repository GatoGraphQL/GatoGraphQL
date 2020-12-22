<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Misc\RequestUtils;

class WSLPoP_SocialLogin_API extends PoP_SocialLogin_API_Base implements PoP_SocialLogin_API
{
    public function getProvider($user_id)
    {
        $usermetaapi = \PoPSchema\UserMeta\FunctionAPIFactory::getInstance();
        return $usermetaapi->getUserMeta($user_id, 'wsl_current_provider', true);
    }

    public function getNetworklinks()
    {
        $current_page_url = RequestUtils::getCurrentUrl();

        $authenticate_base_url = site_url('wp-login.php', 'login_post') . (strpos(site_url('wp-login.php', 'login_post'), '?') ? '&' : '?') . "action=wordpress_social_authenticate&";

        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        // overwrite endpoint_url if need'd
        // if( $cmsengineapi->getOption( 'wsl_settings_hide_wp_login' ) == 1 ){
        //     $authenticate_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/services/authenticate.php?";
        // }

        global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

        if (empty($social_icon_set)) {
            $social_icon_set = "wpzoom/";
        } else {
            $social_icon_set .= "/";
        }
        $assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/' . $social_icon_set;

        // Additions from Leo:
        // Font Awesome icons: replace the original social media logo
        $fontawesomes = array(
            'Facebook' => 'fa-facebook',
            'Google' => 'fa-google-plus',
            'Twitter' => 'fa-twitter',
            'Yahoo' => 'fa-yahoo',
            'LinkedIn' => 'fa-linkedin'
        );
        // Short names: used for giving css hover styles to each social media link
        $shortnames = array(
            'Facebook' => 'facebook',
            'Google' => 'gplus',
            'Twitter' => 'twitter',
            'Yahoo' => 'yahoo',
            'LinkedIn' => 'linkedin'
        );


        $networklinks = array();
        foreach ($WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG as $item) {
            $provider_id     = @ $item["provider_id"];
            $provider_name   = @ $item["provider_name"];

            if ($cmsengineapi->getOption('wsl_settings_' . $provider_id . '_enabled')) {
                $authenticate_url = $authenticate_base_url . "provider=" . $provider_id . "&redirect_to=" . urlencode($current_page_url);
                $networklinks[] = array(
                    'id' => $provider_id,
                    'name' => $provider_name,
                    'short-name' => $shortnames[$provider_id],
                    'url' => $authenticate_url,
                    'title' => sprintf(
                        '%s %s',
                        TranslationAPIFacade::getInstance()->__('Log in with', 'wsl-pop'),
                        $provider_name
                    ),
                    'icon-src' => $assets_base_url . strtolower($provider_id) . '.png',
                    'fontawesome' => 'fa-fw fa-lg '.$fontawesomes[$provider_id]
                );
            }
        }

        return $networklinks;
    }
}

/**
 * Initialize
 */
new WSLPoP_SocialLogin_API();
