<?php

const POP_UNIQUEID = 'unique-id';
const POP_CONSTANT_ENTRYMODULE = 'entry-module';

const GD_URLPARAM_VERSION = 'v';
const GD_URLPARAM_DATASTRUCTURE = 'datastructure';

const GD_URLPARAM_FORMAT = 'format';
const GD_URLPARAM_SETTINGSFORMAT = 'settingsformat';
const GD_URLPARAM_TAB = 'tab';

const GD_URLPARAM_DATAOUTPUTMODE = 'dataoutputmode';
const GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES = 'splitbysources';
const GD_URLPARAM_DATAOUTPUTMODE_COMBINED = 'combined';

const GD_URLPARAM_ACTION = 'action';

const GD_URLPARAM_LAYOUTS = 'layouts';
const GD_URLPARAM_FIELDS = 'fields';

const GD_URLPARAM_BACKGROUNDLOADURLS = 'backgroundload-urls';
const GD_URLPARAM_INTERCEPTURLS = 'intercept-urls';

// Used for the Comments to know what post to fetch comments from when filtering
const GD_URLPARAM_POSTID = 'post_id';

// Used to print "active" in the menu navigation
// const GD_URLPARAM_PARENTPAGEID = 'parentpage_id';
const GD_URLPARAM_TITLE = 'title';
const GD_URLPARAM_TITLELINK = 'title-link';
const GD_URLPARAM_URL = 'url';
const GD_URLPARAM_ERROR = 'error';

const GD_URLPARAM_REDIRECTTO = 'redirect_to';

// Paged param: It is 'pagenum' and not 'paged', because if so WP does a redirect to re-adjust the URL
// From https://www.mesym.com/action?paged=2 it redirects to https://www.mesym.com/action/paged/2/
const GD_URLPARAM_PAGED = 'pagenum';
const GD_URLPARAM_LIMIT = 'limit';

const GD_URLPARAM_MODULEFILTER = 'modulefilter';
const GD_URLPARAM_MODULEPATHS = 'modulepaths';
const GD_URLPARAM_HEADMODULE = 'headmodule';
const GD_URLPARAM_ACTIONPATH = 'actionpath';

const GD_URLPARAM_DATAOUTPUTITEMS = 'dataoutputitems';
const GD_URLPARAM_DATAOUTPUTITEMS_META = 'meta';
const GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS = 'modulesettings';
const GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS = 'datasetmodulesettings';
const GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA = 'moduledata';
const GD_URLPARAM_DATAOUTPUTITEMS_DATABASES = 'databases';
const GD_URLPARAM_DATAOUTPUTITEMS_SESSION = 'session';

const GD_URLPARAM_DATASOURCES = 'datasource';
const GD_URLPARAM_DATASOURCES_ONLYMODEL = 'onlymodel';
const GD_URLPARAM_DATASOURCES_MODELANDREQUEST = 'modelandrequest';

const GD_URLPARAM_EXTRAURIS = 'extrauris';
const POP_JS_MULTIPLEURIS = 'multiple-uris';

const GD_URLPARAM_STOPFETCHING = 'stop-fetching';
