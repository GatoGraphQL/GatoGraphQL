<?php
// Install WordPress plugins
class Temporary_Hacks_Installation
{
    public function __construct()
    {
        if ($_REQUEST['action'] == 'install-hack') {
            $plugin_version = $this->systemActivateplugins(array());
            foreach ($plugin_version as $plugin => $activate_version) {

                // Activate directly, no need to check for the version (which doesn't exist anyway, since the corresponding "-environment" plugin will be deactivated)
                $this->runActivatePlugin("${plugin}/${plugin}.php");
            }

            // Execute the activate method for plugins which are already activate
            add_action('init', array($this, 'reactivatePlugins'));
        }
    }

    public function reactivatePlugins()
    {
    
        // Aryo Activity Log for PoP
        AAL_PoP_Maintenance::activate(false);
    }

    public function runActivatePlugin($plugin)
    {
        $current = getOption('active_plugins');
        $plugin = plugin_basename(trim($plugin));

        if (!in_array($plugin, $current)) {
            $current[] = $plugin;
            sort($current);
            do_action('activate_plugin', trim($plugin));
            update_option('active_plugins', $current);
            do_action('activate_' . trim($plugin));
            do_action('activated_plugin', trim($plugin));
        }

        return null;
    }

    public function systemActivateplugins($plugin_version)
    {

        // Comment Temporary Hack: The commented plugins must not be activated everywhere, then it be installed through /activate-plugins/
        
        // // PoP Multidomain Processors
        // $plugin_version['pop-multidomain-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPMULTIDOMAINPROCESSORS_VERSION;

        // // PoP User Stance
        // $plugin_version['pop-userstance'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERSTANCE_VERSION;

        // // PoP User Stance Frontend
        // $plugin_version['pop-userstance-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERSTANCEFRONTEND_VERSION;

        // // PoP User Stance Processors
        // $plugin_version['pop-userstance-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERSTANCEPROCESSORS_VERSION;

        // // PoP Volunteering
        // $plugin_version['pop-volunteering'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPVOLUNTEERING_VERSION;

        // // PoP Volunteering Frontend
        // $plugin_version['pop-volunteering-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPVOLUNTEERINGFRONTEND_VERSION;

        // // PoP Volunteering Processors
        // $plugin_version['pop-volunteering-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPVOLUNTEERINGPROCESSORS_VERSION;

        // // PoP Multidomain for SPA Resource Loader
        // $plugin_version['pop-multidomain-sparesourceloader'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPMULTIDOMAINSPARESOURCELOADER_VERSION;

        // Public Post Preview PoP
        $plugin_version['public-post-preview-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_PPPPOP_VERSION;

        // PoP FrontendEngine AWS
        $plugin_version['pop-frontendengine-aws'] = CLUSTER_HELPERS_PLUGINACTIVATION_FRONTENDENGINEAWS_VERSION;

        // Disable REST API
        $plugin_version['disable-json-api'] = CLUSTER_HELPERS_PLUGINACTIVATION_DISABLERESTAPI_VERSION;

        // PoP Cluster ResourceLoader
        $plugin_version['pop-cluster-resourceloader'] = CLUSTER_HELPERS_PLUGINACTIVATION_CLUSTERRESOURCELOADER_VERSION;

        // PoP Cluster ResourceLoader for AWS
        $plugin_version['pop-cluster-resourceloader-aws'] = CLUSTER_HELPERS_PLUGINACTIVATION_CLUSTERRESOURCELOADERAWS_VERSION;

        // // PoP Generic Forms
        // $plugin_version['pop-genericforms'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPGENERICFORMS_VERSION;

        // // PoP Generic Forms Processors
        // $plugin_version['pop-genericforms-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPGENERICFORMSPROCESSORS_VERSION;
        
        // Gravity Forms for PoP Generic Forms
        $plugin_version['gravityforms-pop-genericforms'] = CLUSTER_HELPERS_PLUGINACTIVATION_GFPOPGENERICFORMS_VERSION;

        // PoP CDN Foundation
        $plugin_version['pop-cdn-foundation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCDNFOUNDATION_VERSION;

        // WP Super Cache for PoP
        $plugin_version['wp-super-cache-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_WPSUPERCACHEPOP_VERSION;

        // User Avatar
        $plugin_version['user-avatar-popfork'] = CLUSTER_HELPERS_PLUGINACTIVATION_USERAVATARPOPFORK_VERSION;

        // PoP User Avatar
        $plugin_version['pop-useravatar'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_USERAVATAR_VERSION;

        // PoP User Avatar Processors
        $plugin_version['pop-useravatar-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_USERAVATARPROCESSORS_VERSION;

        // PoP User Avatar for AWS
        $plugin_version['pop-useravatar-aws'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_USERAVATARAWS_VERSION;

        // PoP User Avatar Frontend
        $plugin_version['pop-useravatar-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_USERAVATARFRONTEND_VERSION;

        // User Role Editor for PoP
        $plugin_version['user-role-editor-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_UREPOP_VERSION;

        // Host Thumbs for PoP Core Processors
        $plugin_version['pop-mediahostthumbs'] = CLUSTER_HELPERS_PLUGINACTIVATION_HOSTTHUMBS_VERSION;

        // PoP Application
        $plugin_version['pop-application'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPAPPLICATION_VERSION;

        // PoP WordPress Application
        $plugin_version['pop-application-wp'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPAPPLICATIONWP_VERSION;

        // PoP Forms
        $plugin_version['pop-forms'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPFORMS_VERSION;

        // PoP Forms Frontend
        $plugin_version['pop-forms-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPFORMSFRONTEND_VERSION;

        // PoP Forms Processors
        $plugin_version['pop-forms-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPFORMSPROCESSORS_VERSION;

        // PoP CMS
        $plugin_version['pop-cms'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCMS_VERSION;

        // PoP WordPress CMS
        $plugin_version['pop-cms-wp'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCMSWP_VERSION;

        // WP Offload S3 Lite for PoP
        $plugin_version['amazon-s3-and-cloudfront-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_AWSS3CFPOP_VERSION;

        // WP Offload S3 Lite for PoP Frontend
        $plugin_version['amazon-s3-and-cloudfront-pop-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_AWSS3CFPOPFRONTEND_VERSION;

        // PoP Multilingual
        $plugin_version['pop-multilingual'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPMULTILINGUAL_VERSION;

        // PoP Multilingual Frontend
        $plugin_version['pop-multilingual-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPMULTILINGUALFRONTEND_VERSION;

        // PoP Multilingual Processors
        $plugin_version['pop-multilingual-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPMULTILINGUALPROCESSORS_VERSION;

        // qTranslate-X for PoP
        $plugin_version['qtranslate-x-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_QTXPOP_VERSION;

        // Advanced Custom Fields for PoP
        $plugin_version['advanced-custom-fields-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_ACFPOP_VERSION;

        // Co-Authors Plus for PoP
        $plugin_version['co-authors-plus-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_CAPPOP_VERSION;

        // PoP CSS Converter
        $plugin_version['pop-cssconverter'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCSSCONVERTER_VERSION;

        // PoP Engine Frontend
        $plugin_version['pop-engine-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPFRONTENDENGINE_VERSION;

        // PoP Application Processors
        $plugin_version['pop-application-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPAPPLICATIONPROCESSORS_VERSION;

        // Cluster Helpers
        $plugin_version['cluster-helpers'] = CLUSTER_HELPERS_PLUGINACTIVATION_CLUSTERHELPERS_VERSION;

        // Activity Log
        $plugin_version['aryo-activity-log'] = CLUSTER_HELPERS_PLUGINACTIVATION_AAL_VERSION;

        // PoP Engine Processors
        $plugin_version['pop-engine-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPENGINEPROCESSORS_VERSION;

        // PoP Engine Model Processors
        $plugin_version['pop-cmsmodel-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCMSMODELPROCESSORS_VERSION;

        // PoP User Platform
        $plugin_version['pop-userplatform'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERPLATFORM_VERSION;

        // PoP User Platform Processors
        $plugin_version['pop-userplatform-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERPLATFORMPROCESSORS_VERSION;

        // PoP Content Creation
        $plugin_version['pop-contentcreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTCREATION_VERSION;

        // PoP Content Creation Processors
        $plugin_version['pop-contentcreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTCREATIONPROCESSORS_VERSION;

        // PoP User State
        $plugin_version['pop-userstate'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERSTATE_VERSION;

        // PoP User Login
        $plugin_version['pop-userlogin'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERLOGIN_VERSION;

        // PoP User Login Frontend
        $plugin_version['pop-userlogin-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERLOGINFRONTEND_VERSION;

        // PoP User Login Processors
        $plugin_version['pop-userlogin-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERLOGINPROCESSORS_VERSION;

        // PoP Social Network
        $plugin_version['pop-socialnetwork'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSOCIALNETWORK_VERSION;

        // PoP Social Network Processors
        $plugin_version['pop-socialnetwork-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSOCIALNETWORKPROCESSORS_VERSION;

        // PoP Social Media Processors
        $plugin_version['pop-socialmediaproviders-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSOCIALMEDIAPROVIDERSPROCESSORS_VERSION;

        // PoP Comments
        $plugin_version['pop-addcomments'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDCOMMENTS_VERSION;

        // PoP Comments Frontend
        $plugin_version['pop-addcomments-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDCOMMENTSFRONTEND_VERSION;

        // PoP Comments Processors
        $plugin_version['pop-addcomments-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDCOMMENTSPROCESSORS_VERSION;

        // PoP Comments
        $plugin_version['pop-addcomments-tinymce'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDCOMMENTSTINYMCE_VERSION;

        // PoP Comments Processors
        $plugin_version['pop-addcomments-tinymce-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDCOMMENTSTINYMCEPROCESSORS_VERSION;

        // PoP Locations
        $plugin_version['pop-locations'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONS_VERSION;

        // PoP Add Locations
        $plugin_version['pop-addlocations'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDLOCATIONS_VERSION;

        // PoP Events
        $plugin_version['pop-events'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTS_VERSION;

        // PoP Events Creation
        $plugin_version['pop-eventscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTSCREATION_VERSION;

        // PoP Locations Processors
        $plugin_version['pop-locations-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONSPROCESSORS_VERSION;

        // PoP Add Locations Processors
        $plugin_version['pop-addlocations-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDLOCATIONSPROCESSORS_VERSION;

        // PoP Events Processors
        $plugin_version['pop-events-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTSPROCESSORS_VERSION;

        // PoP Events Creation Processors
        $plugin_version['pop-eventscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTSCREATIONPROCESSORS_VERSION;

        // PoP User Avatar Foundation
        $plugin_version['pop-avatar-foundation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPAVATARFOUNDATION_VERSION;

        // User Avatar for PoP
        $plugin_version['user-avatar-popfork-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_USERAVATARPOPFORKPOP_VERSION;

        // PoP User Communities Frontend
        $plugin_version['pop-usercommunities-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERCOMMUNITIESFRONTEND_VERSION;

        // PoP Common User Roles Frontend
        $plugin_version['pop-commonuserroles-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOMMONUSERROLESFRONTEND_VERSION;

        // PoP Application Frontend
        $plugin_version['pop-application-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPAPPLICATIONFRONTEND_VERSION;

        // PoP CMS Model
        $plugin_version['pop-cmsmodel'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCMSMODEL_VERSION;

        // PoP CMS Model WordPress
        $plugin_version['pop-cmsmodel-wp'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCMSMODELWP_VERSION;

        // PoP Blog
        $plugin_version['pop-blog'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBLOG_VERSION;

        // PoP Blog Processors
        $plugin_version['pop-blog-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBLOGPROCESSORS_VERSION;

        // PoP Preview Content
        $plugin_version['pop-previewcontent'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPPREVIEWCONTENT_VERSION;

        // PoP Preview Content Frontend
        $plugin_version['pop-previewcontent-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPPREVIEWCONTENTFRONTEND_VERSION;

        // PoP Notifications
        $plugin_version['pop-notifications'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOTIFICATIONS_VERSION;

        // PoP Notifications Frontend
        $plugin_version['pop-notifications-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOTIFICATIONSFRONTEND_VERSION;

        // PoP Notifications Processors
        $plugin_version['pop-notifications-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOTIFICATIONSPROCESSORS_VERSION;

        // PoP User Communities
        $plugin_version['pop-usercommunities'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERCOMMUNITIES_VERSION;

        // PoP User Communities Processors
        $plugin_version['pop-usercommunities-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERCOMMUNITIESPROCESSORS_VERSION;

        // PoP Common User Roles
        $plugin_version['pop-commonuserroles'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOMMONUSERROLES_VERSION;

        // PoP Common User Roles Processors
        $plugin_version['pop-commonuserroles-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOMMONUSERROLESPROCESSORS_VERSION;

        // PoP Coauthors
        $plugin_version['pop-coauthors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOAUTHORS_VERSION;

        // PoP Coauthors Processors
        $plugin_version['pop-coauthors-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOAUTHORSPROCESSORS_VERSION;

        // PoP Add Coauthors
        $plugin_version['pop-addcoauthors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDCOAUTHORS_VERSION;

        // PoP Add Coauthors Processors
        $plugin_version['pop-addcoauthors-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDCOAUTHORSPROCESSORS_VERSION;

        // // PoP Content Post Links
        // $plugin_version['pop-contentpostlinks'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTPOSTLINKS_VERSION;

        // // PoP Content Post Links Frontend
        // $plugin_version['pop-contentpostlinks-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTPOSTLINKSFRONTEND_VERSION;

        // // PoP Content Post Links Processors
        // $plugin_version['pop-contentpostlinks-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTPOSTLINKSPROCESSORS_VERSION;

        // PoP Posts Creation
        $plugin_version['pop-postscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPPOSTSCREATION_VERSION;

        // PoP Posts Creation Frontend
        $plugin_version['pop-postscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPPOSTSCREATIONFRONTEND_VERSION;

        // PoP Posts Creation Processors
        $plugin_version['pop-postscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPPOSTSCREATIONPROCESSORS_VERSION;

        // // PoP Content Post Links Creation
        // $plugin_version['pop-contentpostlinkscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTPOSTLINKSCREATION_VERSION;

        // // PoP Content Post Links Creation Frontend
        // $plugin_version['pop-contentpostlinkscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTPOSTLINKSCREATIONFRONTEND_VERSION;

        // // PoP Content Post Links Creation Processors
        // $plugin_version['pop-contentpostlinkscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTPOSTLINKSCREATIONPROCESSORS_VERSION;

        // PoP Category Posts
        $plugin_version['pop-categoryposts'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCATEGORYPOSTS_VERSION;

        // PoP Category Posts Frontend
        $plugin_version['pop-categoryposts-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCATEGORYPOSTSFRONTEND_VERSION;

        // PoP Category Posts Processors
        $plugin_version['pop-categoryposts-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCATEGORYPOSTSPROCESSORS_VERSION;

        // PoP Category Posts Creation
        $plugin_version['pop-categorypostscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCATEGORYPOSTSCREATION_VERSION;

        // PoP Category Posts Creation Frontend
        $plugin_version['pop-categorypostscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCATEGORYPOSTSCREATIONFRONTEND_VERSION;

        // PoP Category Posts Creation Processors
        $plugin_version['pop-categorypostscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCATEGORYPOSTSCREATIONPROCESSORS_VERSION;

        // PoP No Search Category Posts
        $plugin_version['pop-nosearchcategoryposts'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOSEARCHCATEGORYPOSTS_VERSION;

        // PoP No Search Category Posts Frontend
        $plugin_version['pop-nosearchcategoryposts-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOSEARCHCATEGORYPOSTSFRONTEND_VERSION;

        // PoP No Search Category Posts Processors
        $plugin_version['pop-nosearchcategoryposts-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOSEARCHCATEGORYPOSTSPROCESSORS_VERSION;

        // PoP No Search Category Posts Creation
        $plugin_version['pop-nosearchcategorypostscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOSEARCHCATEGORYPOSTSCREATION_VERSION;

        // PoP No Search Category Posts Creation Frontend
        $plugin_version['pop-nosearchcategorypostscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOSEARCHCATEGORYPOSTSCREATIONFRONTEND_VERSION;

        // PoP No Search Category Posts Creation Processors
        $plugin_version['pop-nosearchcategorypostscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNOSEARCHCATEGORYPOSTSCREATIONPROCESSORS_VERSION;

        // PoP Add Highlights
        $plugin_version['pop-addhighlights'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDHIGHLIGHTS_VERSION;

        // PoP Add Highlights Frontend
        $plugin_version['pop-addhighlights-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDHIGHLIGHTSFRONTEND_VERSION;

        // PoP Add Highlights Processors
        $plugin_version['pop-addhighlights-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDHIGHLIGHTSPROCESSORS_VERSION;

        // PoP User Platform Frontend
        $plugin_version['pop-userplatform-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERPLATFORMFRONTEND_VERSION;

        // PoP Theme Helpers
        $plugin_version['pop-themehelpers'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTHEMEHELPERS_VERSION;

        // PoP Related Posts
        $plugin_version['pop-relatedposts'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPRELATEDPOSTS_VERSION;

        // PoP Related Posts Frontend
        $plugin_version['pop-relatedposts-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPRELATEDPOSTSFRONTEND_VERSION;

        // PoP Related Posts Processors
        $plugin_version['pop-relatedposts-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPRELATEDPOSTSPROCESSORS_VERSION;

        // PoP Add Related Posts
        $plugin_version['pop-addrelatedposts'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDRELATEDPOSTS_VERSION;

        // PoP Add Related Posts Processors
        $plugin_version['pop-addrelatedposts-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDRELATEDPOSTSPROCESSORS_VERSION;

        // PoP Google Analytics
        $plugin_version['pop-googleanalytics'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPGOOGLEANALYTICS_VERSION;

        // Google Analytics Dashboard for WP PoP
        $plugin_version['google-analytics-dashboard-for-wp-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_GADWPPOP_VERSION;

        // Gravity Forms for PoP
        $plugin_version['gravityforms-pop'] = CLUSTER_HELPERS_PLUGINACTIVATION_GFPOP_VERSION;

        // Gravity Forms for PoP Frontend
        $plugin_version['gravityforms-pop-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_GFPOPFRONTEND_VERSION;

        // Gravity Forms for PoP Processors
        $plugin_version['gravityforms-pop-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_GFPOPPROCESSORS_VERSION;

        // PoP Social Login
        $plugin_version['pop-sociallogin'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSOCIALLOGIN_VERSION;

        // PoP Social Login Frontend
        $plugin_version['pop-sociallogin-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSOCIALLOGINFRONTEND_VERSION;

        // PoP Social Login Processors
        $plugin_version['pop-sociallogin-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSOCIALLOGINPROCESSORS_VERSION;

        // WordPress Social Login for PoP Frontend
        $plugin_version['wordpress-social-login-pop-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_WSLPOPFRONTEND_VERSION;

        // PoP Avatar
        $plugin_version['pop-avatar'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_AVATAR_VERSION;

        // PoP Avatar Processors
        $plugin_version['pop-avatar-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_AVATARPROCESSORS_VERSION;

        // PoP Avatar for AWS
        $plugin_version['pop-avatar-aws'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_AVATARAWS_VERSION;

        // PoP Avatar Frontend
        $plugin_version['pop-avatar-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POP_AVATARFRONTEND_VERSION;

        // Activity Log for PoP Custom Version
        $plugin_version['aryo-activity-log-pop-custom'] = CLUSTER_HELPERS_PLUGINACTIVATION_AALPOPCUSTOM_VERSION;

        // PoP Master Collection Frontend
        $plugin_version['pop-mastercollection-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPMASTERCOLLECTIONFRONTEND_VERSION;

        // PoP Master Collection Processors
        $plugin_version['pop-mastercollection-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPMASTERCOLLECTIONPROCESSORS_VERSION;

        // PoP Social Network Frontend
        $plugin_version['pop-socialnetwork-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSOCIALNETWORKFRONTEND_VERSION;

        // PoP User Roles
        $plugin_version['pop-userroles'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPUSERROLES_VERSION;

        // PoP Locations Frontend
        $plugin_version['pop-locations-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONSFRONTEND_VERSION;

        // PoP Events Frontend
        $plugin_version['pop-events-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTSFRONTEND_VERSION;

        // PoP Add Locations Frontend
        $plugin_version['pop-addlocations-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDLOCATIONSFRONTEND_VERSION;

        // PoP Events Creation Frontend
        $plugin_version['pop-eventscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTSCREATIONFRONTEND_VERSION;

        // // PoP Event Links
        // $plugin_version['pop-eventlinks'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTLINKS_VERSION;

        // // PoP Event Links Creation
        // $plugin_version['pop-eventlinkscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTLINKSCREATION_VERSION;

        // // PoP Event Links Frontend
        // $plugin_version['pop-eventlinks-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTLINKSFRONTEND_VERSION;

        // // PoP Event Links Creation Frontend
        // $plugin_version['pop-eventlinkscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTLINKSCREATIONFRONTEND_VERSION;

        // // PoP Event Links Creation Processors
        // $plugin_version['pop-eventlinkscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEVENTLINKSCREATIONPROCESSORS_VERSION;

        // Events Manager for PoP Locations
        $plugin_version['events-manager-pop-locations'] = CLUSTER_HELPERS_PLUGINACTIVATION_EMPOPLOCATIONS_VERSION;

        // Events Manager for PoP Events
        $plugin_version['events-manager-pop-events'] = CLUSTER_HELPERS_PLUGINACTIVATION_EMPOPEVENTS_VERSION;

        // PoP Location Posts
        $plugin_version['pop-locationposts'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTS_VERSION;

        // PoP Location Posts Frontend
        $plugin_version['pop-locationposts-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTSFRONTEND_VERSION;

        // PoP Location Posts Processors
        $plugin_version['pop-locationposts-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTSPROCESSORS_VERSION;

        // PoP Location Posts Creation
        $plugin_version['pop-locationpostscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTSCREATION_VERSION;

        // // PoP Location Post Links
        // $plugin_version['pop-locationpostlinks'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTLINKS_VERSION;

        // // PoP Location Post Links Creation
        // $plugin_version['pop-locationpostlinkscreation'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTLINKSCREATION_VERSION;

        // // PoP Location Post Links Processors
        // $plugin_version['pop-locationpostlinks-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTLINKSPROCESSORS_VERSION;

        // PoP Location Posts Creation Processors
        $plugin_version['pop-locationpostscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTSCREATIONPROCESSORS_VERSION;

        // // PoP Location Post Links Creation Processors
        // $plugin_version['pop-locationpostlinkscreation-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTLINKSCREATIONPROCESSORS_VERSION;

        // PoP Location Posts Creation Frontend
        $plugin_version['pop-locationpostscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTSCREATIONFRONTEND_VERSION;

        // // PoP Location Post Links Creation Frontend
        // $plugin_version['pop-locationpostlinkscreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTLINKSCREATIONFRONTEND_VERSION;

        // PoP Base Collection Frontend
        $plugin_version['pop-basecollection-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBASECOLLECTIONFRONTEND_VERSION;

        // PoP Base Collection Processors
        $plugin_version['pop-basecollection-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBASECOLLECTIONPROCESSORS_VERSION;

        // PoP Bootstrap Frontend
        $plugin_version['pop-bootstrap-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBOOTSTRAPFRONTEND_VERSION;

        // PoP Bootstrap Processors
        $plugin_version['pop-bootstrap-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBOOTSTRAPPROCESSORS_VERSION;

        // PoP Bootstrap Collection Frontend
        $plugin_version['pop-bootstrapcollection-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBOOTSTRAPCOLLECTIONFRONTEND_VERSION;

        // PoP Bootstrap Collection Processors
        $plugin_version['pop-bootstrapcollection-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBOOTSTRAPCOLLECTIONPROCESSORS_VERSION;

        // PoP Content Creation Frontend
        $plugin_version['pop-contentcreation-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTENTCREATIONFRONTEND_VERSION;

        // PoP Single-Page Application
        $plugin_version['pop-spa'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSPA_VERSION;

        // PoP Single-Page Application Frontend
        $plugin_version['pop-spa-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSPAFRONTEND_VERSION;

        // PoP Single-Page Application Processors
        $plugin_version['pop-spa-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSPAPROCESSORS_VERSION;

        // PoP Server-Side Rendering
        $plugin_version['pop-ssr'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSSR_VERSION;

        // PoP Theme Wassup Frontend
        $plugin_version['poptheme-wassup-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTHEMEWASSUPFRONTEND_VERSION;

        // PoP TinyMCE
        $plugin_version['pop-tinymce'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTINYMCE_VERSION;

        // PoP Newsletter
        $plugin_version['pop-newsletter'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNEWSLETTER_VERSION;

        // PoP Newsletter Frontend
        $plugin_version['pop-newsletter-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNEWSLETTERFRONTEND_VERSION;

        // PoP Newsletter Processors
        $plugin_version['pop-newsletter-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPNEWSLETTERPROCESSORS_VERSION;

        // PoP Resource Loader
        $plugin_version['pop-resourceloader'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPRESOURCELOADER_VERSION;

        // PoP SPA Resource Loader
        $plugin_version['pop-sparesourceloader'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSPARESOURCELOADER_VERSION;

        // PoP TinyMCE Frontend
        $plugin_version['pop-tinymce-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTINYMCEFRONTEND_VERSION;

        // PoP Frontend Engine Optimizations
        $plugin_version['pop-frontendengine-optimizations'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPFRONTENDENGINEOPTIMIZATIONS_VERSION;

        // PoP Email Sender AWS
        $plugin_version['pop-emailsender-aws'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPEMAILSENDERAWS_VERSION;

        // PoP Base 36 Module Names
        $plugin_version['pop-base36modulenames'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBASE36MODULENAMES_VERSION;

        // PoP Persistant Module Names
        $plugin_version['pop-persistantmodulenames'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPPERSISTANTMODULENAMES_VERSION;

        // PoP Persistant Module Names System
        $plugin_version['pop-system-persistantmodulenames'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPPERSISTANTMODULENAMESSYSTEM_VERSION;

        // PoP Common Pages
        $plugin_version['pop-commonpages'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOMMONPAGES_VERSION;

        // PoP Common Pages Frontend
        $plugin_version['pop-commonpages-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOMMONPAGESFRONTEND_VERSION;

        // PoP Common Pages Processors
        $plugin_version['pop-commonpages-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCOMMONPAGESPROCESSORS_VERSION;

        // PoP Cluster Common Pages
        $plugin_version['pop-clustercommonpages'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCLUSTERCOMMONPAGES_VERSION;

        // PoP Cluster Common Pages Frontend
        $plugin_version['pop-clustercommonpages-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCLUSTERCOMMONPAGESFRONTEND_VERSION;

        // PoP Cluster Common Pages Processors
        $plugin_version['pop-clustercommonpages-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCLUSTERCOMMONPAGESPROCESSORS_VERSION;

        // PoP WordPress Application Frontend
        $plugin_version['pop-application-wp-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPAPPLICATIONWPFRONTEND_VERSION;

        // PoP Blog Frontend
        $plugin_version['pop-blog-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPBLOGFRONTEND_VERSION;

        // PoP CMS Model Frontend
        $plugin_version['pop-cmsmodel-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCMSMODELFRONTEND_VERSION;

        // PoP CSS Converter Frontend
        $plugin_version['pop-cssconverter-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCSSCONVERTERFRONTEND_VERSION;

        // PoP System Frontend
        $plugin_version['pop-system-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSYSTEMFRONTEND_VERSION;

        // PoP Contact Us
        $plugin_version['pop-contactus'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTACTUS_VERSION;

        // PoP Contact Us Frontend
        $plugin_version['pop-contactus-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTACTUSFRONTEND_VERSION;

        // PoP Contact Us Processors
        $plugin_version['pop-contactus-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCONTACTUSPROCESSORS_VERSION;

        // PoP Share
        $plugin_version['pop-share'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSHARE_VERSION;

        // PoP Share Frontend
        $plugin_version['pop-share-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSHAREFRONTEND_VERSION;

        // PoP Share Processors
        $plugin_version['pop-share-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPSHAREPROCESSORS_VERSION;

        // Cluster Environment
        $plugin_version['cluster-environment'] = CLUSTER_HELPERS_PLUGINACTIVATION_CLUSTERENVIRONMENT_VERSION;

        // PoP Add Post Links
        $plugin_version['pop-addpostlinks'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDPOSTLINKS_VERSION;

        // PoP Add Post Links Frontend
        $plugin_version['pop-addpostlinks-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDPOSTLINKSFRONTEND_VERSION;

        // PoP Add Post Links Processors
        $plugin_version['pop-addpostlinks-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPADDPOSTLINKSPROCESSORS_VERSION;

        // PoP Hashtags and Mentions Frontend
        $plugin_version['pop-hashtagsandmentions-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPHASHTAGSANDMENTIONSFRONTEND_VERSION;

        // PoP Hashtags and Mentions Processors
        $plugin_version['pop-hashtagsandmentions-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPHASHTAGSANDMENTIONSPROCESSORS_VERSION;

        // PoP Typeahead Frontend
        $plugin_version['pop-typeahead-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTYPEAHEADFRONTEND_VERSION;

        // PoP Typeahead Processors
        $plugin_version['pop-typeahead-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTYPEAHEADPROCESSORS_VERSION;

        // PoP Viewcomponent Headers Frontend
        $plugin_version['pop-viewcomponentheaders-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPVIEWCOMPONENTHEADERSFRONTEND_VERSION;

        // PoP Viewcomponent Headers Processors
        $plugin_version['pop-viewcomponentheaders-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPVIEWCOMPONENTHEADERSPROCESSORS_VERSION;

        // PoP Location Post Category Layouts Processors
        $plugin_version['pop-locationpostcategorylayouts-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPLOCATIONPOSTCATEGORYLAYOUTSPROCESSORS_VERSION;

        // PoP Captcha
        $plugin_version['pop-captcha'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCAPTCHA_VERSION;

        // PoP Captcha Frontend
        $plugin_version['pop-captcha-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCAPTCHAFRONTEND_VERSION;

        // PoP Captcha Processors
        $plugin_version['pop-captcha-processors'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPCAPTCHAPROCESSORS_VERSION;

        // PoP Engine WP
        $plugin_version['pop-engine-wp'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPENGINEWP_VERSION;

        // PoP Frontend Engine WP
        $plugin_version['pop-frontendengine-wp'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPENGINEFRONTENDWP_VERSION;

        // PoP Theme
        $plugin_version['pop-theme'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTHEME_VERSION;

        // PoP Theme Frontend
        $plugin_version['pop-theme-frontend'] = CLUSTER_HELPERS_PLUGINACTIVATION_POPTHEMEFRONTEND_VERSION;

        return $plugin_version;
    }
}

/**
 * Initialize
 */
new Temporary_Hacks_Installation();
