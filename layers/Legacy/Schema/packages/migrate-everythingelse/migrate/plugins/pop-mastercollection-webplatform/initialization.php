<?php

use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;

class PoP_MasterCollectionWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-mastercollection-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction('popcms:enqueueScripts', $this->registerScripts(...));
            \PoP\Root\App::addAction('popcms:printStyles', $this->registerStyles(...), 100);
        }

        /**
         * Load the PoP Library
         */
        require_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        require_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';

            /* ------------------------------
             * 3rd Party Libraries (using CDN whenever possible)
             ----------------------------- */

            // For GMaps.js
            $cmswebplatformapi->registerScript('googlemaps', getGooglemapsUrl(), null, null);
            $cmswebplatformapi->enqueueScript('googlemaps');

            // IMPORTANT: Don't change the order of enqueuing of files!
            // Register Bootstrap only after registering all other .js files which depend on jquery-ui, so bootstrap goes last in the Javascript stack
            // If before, it produces an error with $('btn').button('loading')
            // http://stackoverflow.com/questions/13235578/bootstrap-radio-buttons-toggle-issue

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {

                // CDN
                // https://github.com/noraesae/perfect-scrollbar/releases
                $cmswebplatformapi->registerScript('perfect-scrollbar', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.11/js/min/perfect-scrollbar.jquery.min.js', null, null);

                // Comment Leo 15/08/2017: We don't use Modernizr anymore after commenting `Modernizr.localstorage` in function supportsHtml5Storage()
                // https://github.com/Modernizr/Modernizr/releases
                // $cmswebplatformapi->registerScript('modernizr', 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', null, null);

                // // http://handlebarsjs.com/installation.html
                // // // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
                // $cmswebplatformapi->registerScript('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.10/handlebars.runtime.min.js', null, null);
                // // $cmswebplatformapi->registerScript('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.runtime.min.js', null, null);

                // https://github.com/hpneo/gmaps/releases
                $cmswebplatformapi->registerScript('gmaps', 'https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.min.js', array('googlemaps'), null);

                // Important: add dependency 'jquery-ui-dialog' to bootstrap. If not, when loading library 'fileupload' in pop-useravatar plug-in, it produces a JS error
                // Uncaught Error: cannot call methods on button prior to initialization; attempted to call method 'loading'

                // https://getbootstrap.com/getting-started/#download
                // $cmswebplatformapi->registerScript('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', array('jquery', 'jquery-ui-dialog'), null);

                // https://github.com/carhartl/jquery-cookie/releases
                $cmswebplatformapi->registerScript('jquery.cookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array('jquery'), null);

                // https://github.com/imakewebthings/waypoints/releases
                $cmswebplatformapi->registerScript('waypoints', 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js', array('jquery'), null);

                // https://github.com/twitter/typeahead.js/releases
                $cmswebplatformapi->registerScript('typeahead', 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js', array('bootstrap'), null);

                $cmswebplatformapi->registerScript('fullscreen', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-fullscreen-plugin/1.1.4/jquery.fullscreen-min.js', null, null);
            } else {

                // Local files
                $cmswebplatformapi->registerScript('perfect-scrollbar', $cdn_js_folder . '/perfect-scrollbar.jquery.0.6.11.min.js', null, null);
                $cmswebplatformapi->registerScript('modernizr', $cdn_js_folder . '/modernizr.2.8.3.min.js', null, null);
                // // // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
                // $cmswebplatformapi->registerScript('handlebars', $cdn_js_folder . '/handlebars.runtime.4.0.10.min.js', null, null);
                // // $cmswebplatformapi->registerScript('handlebars', $cdn_js_folder . '/handlebars.runtime.4.0.5.min.js', null, null);
                $cmswebplatformapi->registerScript('gmaps', $cdn_js_folder . '/gmaps.0.4.24.min.js', array('googlemaps'), null);

                // $cmswebplatformapi->registerScript('bootstrap', $cdn_js_folder . '/bootstrap.3.3.7.min.js', array('jquery', 'jquery-ui-dialog'), null);
                $cmswebplatformapi->registerScript('jquery.cookie', $cdn_js_folder . '/jquery.cookie.1.4.1.min.js', array('jquery'), null);
                $cmswebplatformapi->registerScript('waypoints', $cdn_js_folder . '/jquery.waypoints.4.0.1.min.js', array('jquery'), null);

                $cmswebplatformapi->registerScript('typeahead', $cdn_js_folder . '/typeahead.bundle.0.11.1.min.js', array('bootstrap'), null);

                $cmswebplatformapi->registerScript('fullscreen', $cdn_js_folder . '/jquery.fullscreen-min.js', null);
            }

            // Modernizr to solve the problems of IE incompatibility ('placeholder' not supported)
            $cmswebplatformapi->enqueueScript('perfect-scrollbar');
            $cmswebplatformapi->enqueueScript('modernizr');
            // $cmswebplatformapi->enqueueScript('handlebars');
            $cmswebplatformapi->enqueueScript('gmaps');

            // $cmswebplatformapi->enqueueScript('bootstrap');
            $cmswebplatformapi->enqueueScript('jquery.cookie');
            $cmswebplatformapi->enqueueScript('waypoints');

            // Twitter typeahead: http://twitter.github.io/typeahead.js/
            $cmswebplatformapi->enqueueScript('typeahead');

            $cmswebplatformapi->enqueueScript('fullscreen');

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-mastercollection-webplatform-templates', $bundles_js_folder . '/pop-mastercollection.templates.bundle.min.js', array(), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-webplatform-templates');

                $cmswebplatformapi->registerScript('pop-mastercollection-webplatform', $bundles_js_folder . '/pop-mastercollection.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-mastercollection-menus', $libraries_js_folder.'/menus'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-menus');

                /** Handlebars Helpers */

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-arrays', $libraries_js_folder.'/handlebars-helpers/arrays'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-arrays');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-compare', $libraries_js_folder.'/handlebars-helpers/compare'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-compare');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-date', $libraries_js_folder.'/handlebars-helpers/date'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-date');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-dbobject', $libraries_js_folder.'/handlebars-helpers/dbobject'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-dbobject');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-labels', $libraries_js_folder.'/handlebars-helpers/labels'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-labels');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-mod', $libraries_js_folder.'/handlebars-helpers/mod'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-mod');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-multilayout', $libraries_js_folder.'/handlebars-helpers/multilayout'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-multilayout');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-operators', $libraries_js_folder.'/handlebars-helpers/operators'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-operators');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-showmore', $libraries_js_folder.'/handlebars-helpers/showmore'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-showmore');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-status', $libraries_js_folder.'/handlebars-helpers/status'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-status');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-replace', $libraries_js_folder.'/handlebars-helpers/replace'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-replace');

                $cmswebplatformapi->registerScript('popcore-helpers-handlebars-latestcount', $libraries_js_folder.'/handlebars-helpers/latestcount'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('popcore-helpers-handlebars-latestcount');

                $cmswebplatformapi->registerScript('popcore-helpers-handlebars-feedbackmessage', $libraries_js_folder.'/handlebars-helpers/feedbackmessage'.$suffix.'.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('popcore-helpers-handlebars-feedbackmessage');

                /** Libraries not under CDN */
                $cmswebplatformapi->registerScript('jquery-dynamic-max-height', $includes_js_folder . '/jquery.dynamicmaxheight.min.js', array('jquery'));
                $cmswebplatformapi->enqueueScript('jquery-dynamic-max-height');

                // $cmswebplatformapi->registerScript('pop-custombootstrap', $libraries_js_folder.'/custombootstrap.js', array('jquery', 'pop', 'bootstrap'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                // $cmswebplatformapi->enqueueScript('pop-custombootstrap');

                $cmswebplatformapi->registerScript('pop-system', $libraries_js_folder.'/system'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-system');

                // $cmswebplatformapi->registerScript('pop-bootstrapprocessors-bootstrap', $libraries_js_folder.'/bootstrap.js', array('jquery', 'pop', 'bootstrap'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                // $cmswebplatformapi->enqueueScript('pop-bootstrapprocessors-bootstrap');

                $cmswebplatformapi->registerScript('pop-mastercollection-windows', $libraries_js_folder.'/windows'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-windows');

                $cmswebplatformapi->registerScript('pop-mastercollection-cookies', $libraries_js_folder.'/cookies'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-cookies');

                $cmswebplatformapi->registerScript('pop-mastercollection-expand', $libraries_js_folder.'/expand'.$suffix.'.js', array('pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-expand');

                $cmswebplatformapi->registerScript('pop-mastercollection-functions', $libraries_js_folder.'/functions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-functions');

                $cmswebplatformapi->registerScript('pop-mastercollection-inputfunctions', $libraries_js_folder.'/input-functions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-inputfunctions');

                $cmswebplatformapi->registerScript('pop-mastercollection-embedfunctions', $libraries_js_folder.'/embed-functions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-embedfunctions');

                $cmswebplatformapi->registerScript('pop-mastercollection-printfunctions', $libraries_js_folder.'/print-functions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-printfunctions');

                $cmswebplatformapi->registerScript('pop-mastercollection-dynamicrender', $libraries_js_folder.'/dynamic-render'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-dynamicrender');

                $cmswebplatformapi->registerScript('pop-mastercollection-dynamicrender-urlfunctions', $libraries_js_folder.'/dynamic-render-urlfunctions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-dynamicrender-urlfunctions');

                $cmswebplatformapi->registerScript('pop-mastercollection-targetfunctions', $libraries_js_folder.'/target-functions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-targetfunctions');

                $cmswebplatformapi->registerScript('pop-mastercollection-socialmedia', $libraries_js_folder.'/socialmedia'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-socialmedia');

                $cmswebplatformapi->registerScript('pop-mastercollection-embeddable', $libraries_js_folder.'/embeddable'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-embeddable');

                $cmswebplatformapi->registerScript('pop-mastercollection-blockdataquery', $libraries_js_folder.'/block-dataquery'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-blockdataquery');

                $cmswebplatformapi->registerScript('pop-mastercollection-blockdataquery-userstate', $libraries_js_folder.'/block-dataquery-userstate'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-blockdataquery-userstate');

                $cmswebplatformapi->registerScript('pop-mastercollection-blockgroupdataquery', $libraries_js_folder.'/blockgroup-dataquery'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-blockgroupdataquery');

                $cmswebplatformapi->registerScript('pop-mastercollection-datasetcount', $libraries_js_folder.'/dataset-count'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-datasetcount');

                $cmswebplatformapi->registerScript('pop-mastercollection-links', $libraries_js_folder.'/links'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-links');

                $cmswebplatformapi->registerScript('pop-mastercollection-classes', $libraries_js_folder.'/classes'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-classes');

                $cmswebplatformapi->registerScript('pop-mastercollection-scrolls', $libraries_js_folder.'/scrolls'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-scrolls');

                $cmswebplatformapi->registerScript('pop-mastercollection-onlineoffline', $libraries_js_folder.'/onlineoffline'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-onlineoffline');

                $cmswebplatformapi->registerScript('pop-mastercollection-eventreactions', $libraries_js_folder.'/event-reactions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-eventreactions');

                $cmswebplatformapi->registerScript('pop-mastercollection-eventreactions-userstate', $libraries_js_folder.'/event-reactions-userstate'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-eventreactions-userstate');

                $cmswebplatformapi->registerScript('pop-mastercollection-feedback-message', $libraries_js_folder.'/feedback-message'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-feedback-message');

                // $cmswebplatformapi->registerScript('pop-mastercollection-blockfunctions', $libraries_js_folder.'/block-functions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                // $cmswebplatformapi->enqueueScript('pop-mastercollection-blockfunctions');

                $cmswebplatformapi->registerScript('pop-mastercollection-controls', $libraries_js_folder.'/controls'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-controls');

                $cmswebplatformapi->registerScript('pop-mastercollection-multiselect', $libraries_js_folder.'/3rdparties/multiselect'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-multiselect');

                $cmswebplatformapi->registerScript('pop-mastercollection-daterange', $libraries_js_folder.'/3rdparties/daterange'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-daterange');

                $cmswebplatformapi->registerScript('pop-mastercollection-dynamicmaxheight', $libraries_js_folder.'/3rdparties/dynamicmaxheight'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-dynamicmaxheight');

                $cmswebplatformapi->registerScript('pop-mastercollection-waypoints', $libraries_js_folder.'/3rdparties/waypoints/waypoints'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-waypoints');

                $cmswebplatformapi->registerScript('pop-mastercollection-waypoints-fetchmore', $libraries_js_folder.'/3rdparties/waypoints/waypoints-fetchmore'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-waypoints-fetchmore');

                $cmswebplatformapi->registerScript('pop-mastercollection-waypoints-historystate', $libraries_js_folder.'/3rdparties/waypoints/waypoints-historystate'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-waypoints-historystate');

                $cmswebplatformapi->registerScript('pop-mastercollection-waypoints-theater', $libraries_js_folder.'/3rdparties/waypoints/waypoints-theater'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-waypoints-theater');

                $cmswebplatformapi->registerScript('pop-mastercollection-waypoints-toggleclass', $libraries_js_folder.'/3rdparties/waypoints/waypoints-toggleclass'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-waypoints-toggleclass');

                $cmswebplatformapi->registerScript('pop-mastercollection-waypoints-togglecollapse', $libraries_js_folder.'/3rdparties/waypoints/waypoints-togglecollapse'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-waypoints-togglecollapse');

                $cmswebplatformapi->registerScript('pop-mastercollection-typeahead', $libraries_js_folder.'/3rdparties/typeahead/typeahead'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-typeahead');

                $cmswebplatformapi->registerScript('pop-mastercollection-typeahead-search', $libraries_js_folder.'/3rdparties/typeahead/typeahead-search'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-typeahead-search');

                $cmswebplatformapi->registerScript('pop-mastercollection-typeahead-fetchlink', $libraries_js_folder.'/3rdparties/typeahead/typeahead-fetchlink'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-typeahead-fetchlink');

                $cmswebplatformapi->registerScript('pop-mastercollection-typeahead-selectable', $libraries_js_folder.'/3rdparties/typeahead/typeahead-selectable'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-typeahead-selectable');

                $cmswebplatformapi->registerScript('pop-mastercollection-typeahead-validate', $libraries_js_folder.'/3rdparties/typeahead/typeahead-validate'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-typeahead-validate');

                $cmswebplatformapi->registerScript('pop-mastercollection-typeaheadstorage', $libraries_js_folder.'/3rdparties/typeahead/typeahead-storage'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-typeaheadstorage');

                $cmswebplatformapi->registerScript('pop-mastercollection-typeahead-suggestions', $libraries_js_folder.'/3rdparties/typeahead/typeahead-suggestions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-typeahead-suggestions');

                // $cmswebplatformapi->registerScript('pop-mastercollection-googleanalytics', $libraries_js_folder.'/3rdparties/analytics'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                // $cmswebplatformapi->enqueueScript('pop-mastercollection-googleanalytics');

                $cmswebplatformapi->registerScript('pop-mastercollection-perfectscrollbar', $libraries_js_folder.'/3rdparties/perfectscrollbar'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-perfectscrollbar');

                $cmswebplatformapi->registerScript('pop-mastercollection-tabs', $libraries_js_folder.'/tabs'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-tabs');

                $cmswebplatformapi->registerScript('pop-mastercollection-mentions', $libraries_js_folder.'/mentions'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-mentions');

                $cmswebplatformapi->registerScript('pop-mastercollection-addeditpost', $libraries_js_folder.'/addeditpost'.$suffix.'.js', array('jquery', 'pop'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-mastercollection-addeditpost');

                // Comment Leo 19/11/2017: load the appshell file if either: enabling the config, using the appshell, or allowing for Service Workers, which use the appshell to load content when offline
                if (PoP_WebPlatform_ServerUtils::useAppshell() || (defined('POP_SERVICEWORKERS_INITIALIZED') && !PoP_ServiceWorkers_ServerUtils::disableServiceworkers())) {
                    $cmswebplatformapi->registerScript('pop-appshell', $libraries_js_folder.'/appshell'.$suffix.'.js', array(), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
                    $cmswebplatformapi->enqueueScript('pop-appshell');
                }

                /** Templates */
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/dist/templates/';

        // All MESYM Theme Templates
        $cmswebplatformapi->enqueueScript('dataload-tmpl', $folder.'dataload.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('anchor-control-tmpl', $folder.'anchor-control.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('block-tmpl', $folder.'block.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('basicblock-tmpl', $folder.'basicblock.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('button-control-tmpl', $folder.'button-control.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('buttongroup-tmpl', $folder.'buttongroup.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('button-tmpl', $folder.'button.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('buttoninner-tmpl', $folder.'buttoninner.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('window-tmpl', $folder.'window.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('offcanvas-tmpl', $folder.'offcanvas.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('htmlcode-tmpl', $folder.'htmlcode.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('scriptcode-tmpl', $folder.'scriptcode.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('stylecode-tmpl', $folder.'stylecode.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('conditionwrapper-tmpl', $folder.'conditionwrapper.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('controlbuttongroup-tmpl', $folder.'controlbuttongroup.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('controlgroup-tmpl', $folder.'controlgroup.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('divider-tmpl', $folder.'divider.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('dropdownbutton-control-tmpl', $folder.'dropdownbutton-control.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('fetchmore-tmpl', $folder.'fetchmore.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('hideifempty-tmpl', $folder.'hideifempty.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('latestcount-tmpl', $folder.'latestcount.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-maxheight-tmpl', $folder.'layout-maxheight.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-content-tmpl', $folder.'layout-content.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-linkcontent-tmpl', $folder.'layout-linkcontent.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-userpostinteraction-tmpl', $folder.'layout-userpostinteraction.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-author-contact-tmpl', $folder.'layout-author-contact.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-comment-tmpl', $folder.'layout-comment.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-scriptframe-tmpl', $folder.'layout-scriptframe.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-categories-tmpl', $folder.'layout-categories.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-embedpreview-tmpl', $folder.'layout-embedpreview.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-initjs-delay-tmpl', $folder.'layout-initjs-delay.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-fullobjecttitle-tmpl', $folder.'layout-fullobjecttitle.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-fullview-tmpl', $folder.'layout-fullview.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-fulluser-tmpl', $folder.'layout-fulluser.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-menu-anchor-tmpl', $folder.'layout-menu-anchor.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-menu-collapsesegmentedbutton-tmpl', $folder.'layout-menu-collapsesegmentedbutton.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-menu-collapsesegmentedbutton-tmpl', $folder.'layout-menu-collapsesegmentedbutton.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-menu-dropdown-tmpl', $folder.'layout-menu-dropdown.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-menu-dropdownbutton-tmpl', $folder.'layout-menu-dropdownbutton.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-menu-indent-tmpl', $folder.'layout-menu-indent.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-menu-multitargetindent-tmpl', $folder.'layout-menu-multitargetindent.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-feedbackmessage-tmpl', $folder.'layout-feedbackmessage.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-poststatusdate-tmpl', $folder.'layout-poststatusdate.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-taginfo-tmpl', $folder.'layout-taginfo.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-subcomponent-tmpl', $folder.'layout-subcomponent.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-marker-tmpl', $folder.'layout-marker.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-multiple-tmpl', $folder.'layout-multiple.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-pagetab-tmpl', $folder.'layout-pagetab.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-popover-tmpl', $folder.'layout-popover.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-postadditional-multilayout-label-tmpl', $folder.'layout-postadditional-multilayout-label.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-postthumb-tmpl', $folder.'layout-postthumb.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-previewpost-tmpl', $folder.'layout-previewpost.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-previewuser-tmpl', $folder.'layout-previewuser.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-segmentedbutton-link-tmpl', $folder.'layout-segmentedbutton-link.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-segmentedbutton-link-tmpl', $folder.'layout-segmentedbutton-link.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-styles-tmpl', $folder.'layout-styles.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-authorcontent-tmpl', $folder.'layout-authorcontent.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutpost-authorname-tmpl', $folder.'layoutpost-authorname.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutpost-date-tmpl', $folder.'layoutpost-date.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutpost-status-tmpl', $folder.'layoutpost-status.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutpost-typeahead-component-tmpl', $folder.'layoutpost-typeahead-component.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutpost-card-tmpl', $folder.'layoutpost-card.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutcomment-card-tmpl', $folder.'layoutcomment-card.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-tag-tmpl', $folder.'layout-tag.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutstatic-typeahead-component-tmpl', $folder.'layoutstatic-typeahead-component.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutuser-quicklinks-tmpl', $folder.'layoutuser-quicklinks.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutuser-typeahead-component-tmpl', $folder.'layoutuser-typeahead-component.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutuser-card-tmpl', $folder.'layoutuser-card.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutuser-mention-component-tmpl', $folder.'layoutuser-mention-component.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layouttag-typeahead-component-tmpl', $folder.'layouttag-typeahead-component.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layouttag-mention-component-tmpl', $folder.'layouttag-mention-component.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('feedbackmessage-inner-tmpl', $folder.'feedbackmessage-inner.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        // $cmswebplatformapi->enqueueScript('checkpointmessage-inner-tmpl', $folder.'checkpointmessage-inner.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('message-tmpl', $folder.'message.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('radiobutton-control-tmpl', $folder.'radiobutton-control.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('script-append-tmpl', $folder.'script-append.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('script-latestcount-tmpl', $folder.'script-latestcount.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('script-append-comment-tmpl', $folder.'script-append-comment.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('script-lazyloading-remove-tmpl', $folder.'script-lazyloading-remove.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-lazyloading-spinner-tmpl', $folder.'layout-lazyloading-spinner.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('scroll-inner-tmpl', $folder.'scroll-inner.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('scroll-tmpl', $folder.'scroll.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('sm-item-tmpl', $folder.'sm-item.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('sm-tmpl', $folder.'sm.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('status-tmpl', $folder.'status.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('submenu-tmpl', $folder.'submenu.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('table-inner-tmpl', $folder.'table-inner.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('table-tmpl', $folder.'table.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-button-tmpl', $folder.'viewcomponent-button.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-header-commentclipped-tmpl', $folder.'viewcomponent-header-commentclipped.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-header-commentpost-tmpl', $folder.'viewcomponent-header-commentpost.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-header-replycomment-tmpl', $folder.'viewcomponent-header-replycomment.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-header-post-tmpl', $folder.'viewcomponent-header-post.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-header-user-tmpl', $folder.'viewcomponent-header-user.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-header-tag-tmpl', $folder.'viewcomponent-header-tag.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('widget-tmpl', $folder.'widget.tmpl.js', array('handlebars'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, true);
    }

    public function registerStyles()
    {
        $css_folder = POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/css';
        $dist_css_folder = $css_folder . '/dist';

        /* ------------------------------
         * Wordpress Styles
         ----------------------------- */
        $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();

        // Media Player for the Resources section
        $htmlcssplatformapi->enqueueStyle('wp-mediaelement');

        // Do ALWAYS print the wp_editor editor.min.css file. This is because in the logic in function wp_editor, it will print it only for
        // the first editor (which happend to be the one for Add Project), so first initializing any other editor than this first one it would not show properly
        // Taken from public static function editor( $content, $editor_id, $settings = array() ) {
        wp_print_styles('editor-buttons');

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {

                // CDN
                $htmlcssplatformapi->registerStyle('perfect-scrollbar', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.5/css/perfect-scrollbar.min.css', null, null);
            } else {

                // Locally stored files
                $htmlcssplatformapi->registerStyle('perfect-scrollbar', $cdn_css_folder . '/perfect-scrollbar.0.6.5.min.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('perfect-scrollbar');

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $bundles_css_folder = $dist_css_folder . '/bundles';

                // Plug-in bundle
                $htmlcssplatformapi->registerStyle('pop-mastercollection', $bundles_css_folder . '/pop-mastercollection.bundle.min.css', array('bootstrap'), POP_MASTERCOLLECTIONWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-mastercollection');
            } else {

                // Not in CDN
                $htmlcssplatformapi->registerStyle('jquery-dynamic-max-height', $includes_css_folder . '/jquery.dynamicmaxheight.css', null, POP_MASTERCOLLECTIONWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('jquery-dynamic-max-height');
            }
        }
    }
}
