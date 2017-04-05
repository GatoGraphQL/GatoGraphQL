<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_UNIQUEID', 'unique-id');
// define ('POP_ORIGINALUNIQUEID', 'original-unique-id');

define ('GD_URLPARAM_PAGESECTION_SETTINGSID', 'pagesection');

define ('GD_URLPARAM_VERSION', 'v');
define ('GD_URLPARAM_OUTPUT', 'output');
define ('GD_URLPARAM_OUTPUT_JSON', 'json');
define ('GD_URLPARAM_DATASTRUCTURE', 'datastructure');

define ('GD_URLPARAM_MANGLED', 'mangled');
define ('GD_URLPARAM_MANGLED_NONE', 'none');

define ('GD_URLPARAM_FORMAT', 'format');
define ('GD_URLPARAM_THEME', 'theme');
// Comment Leo 19/02/2016: Renamed GD_URLPARAM_THEMEMODE from 'mode' to 'thememode', because plug-in Wordpress Social Login
// uses param 'mode' for its own purposes, and they conflict, making it not able to log-in the user into FB/Twitter
define ('GD_URLPARAM_THEMEMODE', 'thememode');
define ('GD_URLPARAM_THEMESTYLE', 'themestyle');
define ('GD_URLPARAM_SETTINGSFORMAT', 'settingsformat');
define ('GD_URLPARAM_TAB', 'tab');

define ('GD_URLPARAM_TIMESTAMP', 'timestamp');
define ('GD_URLPARAM_ACTION', 'action');
define ('GD_URLPARAM_ACTION_LATEST', 'latest');
// define ('GD_URLPARAM_SKIPPARAMS', 'skipparams');

define ('GD_URLPARAM_LAYOUTS', 'layouts');
define ('GD_URLPARAM_FIELDS', 'fields');

define ('GD_URLPARAM_BACKGROUNDLOADURLS', 'backgroundload-urls');

// Used for the Comments to know what post to fetch comments from when filtering
define ('GD_URLPARAM_POSTID', 'post_id');

// Used to print "active" in the menu navigation
define ('GD_URLPARAM_PARENTPAGEID', 'parentpage_id');
define ('GD_URLPARAM_TITLE', 'title');
define ('GD_URLPARAM_TITLELINK', 'title-link');
define ('GD_URLPARAM_URL', 'url');
define ('GD_URLPARAM_ERROR', 'error');
define ('GD_URLPARAM_SILENTDOCUMENT', 'silentdocument');
define ('GD_URLPARAM_STORELOCAL', 'storelocal');

define ('GD_URLPARAM_REDIRECTTO', 'redirect_to');

// Paged param: It is 'pagenum' and not 'paged', because if so WP does a redirect to re-adjust the URL
// From https://www.mesym.com/action?paged=2 it redirects to https://www.mesym.com/action/paged/2/
define ('GD_URLPARAM_PAGED', 'pagenum');
define ('GD_URLPARAM_LIMIT', 'limit');

define ('GD_URLPARAM_OPERATION_APPEND', 'append');
define ('GD_URLPARAM_OPERATION_PREPEND', 'prepend');
define ('GD_URLPARAM_OPERATION_REPLACE', 'replace');
define ('GD_URLPARAM_OPERATION_REPLACEINLINE', 'replace-inline');

define ('GD_URLPARAM_MODULE', 'module');
define ('GD_URLPARAM_MODULE_SETTINGS', 'settings');
define ('GD_URLPARAM_MODULE_SETTINGSDATA', 'settingsdata');
define ('GD_URLPARAM_MODULE_DATA', 'data');
define ('GD_URLPARAM_TARGET', 'target');
define ('GD_URLPARAM_TARGET_MAIN', 'main');

define ('GD_URLPARAM_HIDDENIFEMPTY', 'hidden-if-empty');
define ('GD_URLPARAM_HIDEBLOCK', 'hide-block');
define ('GD_URLPARAM_STOPFETCHING', 'stop-fetching');

define ('GD_URLPARAM_VALIDATECHECKPOINTS', 'validate-checkpoints');
