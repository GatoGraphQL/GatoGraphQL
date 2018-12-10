<?php

define ('POP_UNIQUEID', 'unique-id');
define ('POP_CONSTANT_ENTRYMODULE', 'entry-module');

define ('GD_URLPARAM_VERSION', 'v');
define ('GD_URLPARAM_DATASTRUCTURE', 'datastructure');

define ('GD_URLPARAM_FORMAT', 'format');
define ('GD_URLPARAM_THEME', 'theme');
// Comment Leo 19/02/2016: Renamed GD_URLPARAM_THEMEMODE from 'mode' to 'thememode', because plug-in Wordpress Social Login
// uses param 'mode' for its own purposes, and they conflict, making it not able to log-in the user into FB/Twitter
define ('GD_URLPARAM_THEMEMODE', 'thememode');
define ('GD_URLPARAM_THEMESTYLE', 'themestyle');
define ('GD_URLPARAM_SETTINGSFORMAT', 'settingsformat');
define ('GD_URLPARAM_TAB', 'tab');

define ('GD_URLPARAM_DATAOUTPUTMODE', 'dataoutputmode');
define ('GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES', 'splitbysources');
define ('GD_URLPARAM_DATAOUTPUTMODE_COMBINED', 'combined');

define ('GD_URLPARAM_ACTION', 'action');

define ('GD_URLPARAM_LAYOUTS', 'layouts');
define ('GD_URLPARAM_FIELDS', 'fields');

define ('GD_URLPARAM_BACKGROUNDLOADURLS', 'backgroundload-urls');
define ('GD_URLPARAM_INTERCEPTURLS', 'intercept-urls');

// Used for the Comments to know what post to fetch comments from when filtering
define ('GD_URLPARAM_POSTID', 'post_id');

// Used to print "active" in the menu navigation
// define ('GD_URLPARAM_PARENTPAGEID', 'parentpage_id');
define ('GD_URLPARAM_TITLE', 'title');
define ('GD_URLPARAM_TITLELINK', 'title-link');
define ('GD_URLPARAM_URL', 'url');
define ('GD_URLPARAM_ERROR', 'error');

define ('GD_URLPARAM_REDIRECTTO', 'redirect_to');

// Paged param: It is 'pagenum' and not 'paged', because if so WP does a redirect to re-adjust the URL
// From https://www.mesym.com/action?paged=2 it redirects to https://www.mesym.com/action/paged/2/
define ('GD_URLPARAM_PAGED', 'pagenum');
define ('GD_URLPARAM_LIMIT', 'limit');

define ('GD_URLPARAM_MODULEFILTER', 'modulefilter');
define ('GD_URLPARAM_MODULEPATHS', 'modulepaths');
define ('GD_URLPARAM_HEADMODULE', 'headmodule');
define ('GD_URLPARAM_ACTIONPATH', 'actionpath');

define ('GD_URLPARAM_DATAOUTPUTITEMS', 'dataoutputitems');
define ('GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS', 'modulesettings');
define ('GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA', 'moduledata');
define ('GD_URLPARAM_DATAOUTPUTITEMS_DATABASES', 'databases');
define ('GD_URLPARAM_DATAOUTPUTITEMS_SESSION', 'session');

define ('GD_URLPARAM_DATASOURCES', 'datasources');
define ('GD_URLPARAM_DATASOURCES_ONLYMODEL', 'onlymodel');
define ('GD_URLPARAM_DATASOURCES_MODELANDREQUEST', 'modelandrequest');

define ('GD_URLPARAM_EXTRAURIS', 'extrauris');
define ('POP_JS_MULTIPLEURIS', 'multiple-uris');

define ('GD_URLPARAM_STOPFETCHING', 'stop-fetching');
