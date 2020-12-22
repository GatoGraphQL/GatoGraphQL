<?php

const POP_UNIQUEID = 'unique-id';
const POP_CONSTANT_ENTRYMODULE = 'entrymodule';

const GD_URLPARAM_VERSION = 'version';
const GD_URLPARAM_DATASTRUCTURE = 'datastructure';

const GD_URLPARAM_FORMAT = 'format';
const GD_URLPARAM_SETTINGSFORMAT = 'settingsformat';


const GD_URLPARAM_DATAOUTPUTMODE = 'dataoutputmode';
const GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES = 'splitbysources';
const GD_URLPARAM_DATAOUTPUTMODE_COMBINED = 'combined';

const GD_URLPARAM_DATABASESOUTPUTMODE = 'dboutputmode';
const GD_URLPARAM_DATABASESOUTPUTMODE_SPLITBYDATABASES = 'splitbydbs';
const GD_URLPARAM_DATABASESOUTPUTMODE_COMBINED = 'combined';

const GD_URLPARAM_ACTIONS = 'actions';
const GD_URLPARAM_SCHEME = 'scheme';

const GD_URLPARAM_STRATUM = 'stratum';

const GD_URLPARAM_BACKGROUNDLOADURLS = 'backgroundloadurls';
const GD_URLPARAM_INTERCEPTURLS = 'intercept-urls';

// Used for the Comments to know what post to fetch comments from when filtering
const GD_URLPARAM_COMMENTPOSTID = 'commentpid';

// Used to print "active" in the menu navigation
// const GD_URLPARAM_PARENTPAGEID = 'parentpage_id';
const GD_URLPARAM_TITLE = 'title';
const GD_URLPARAM_TITLELINK = 'title-link';
const GD_URLPARAM_URL = 'url';
const GD_URLPARAM_ERROR = 'error';

const GD_URLPARAM_REDIRECTTO = 'redirect_to';

// Paged param: It is 'pagenum' and not 'paged', because if so WP does a redirect to re-adjust the URL
// From https://www.mesym.com/action?paged=2 it redirects to https://www.mesym.com/action/paged/2/
const GD_URLPARAM_PAGENUMBER = 'pagenum';
const GD_URLPARAM_LIMIT = 'limit';

const GD_URLPARAM_ACTIONPATH = 'actionpath';

const GD_URLPARAM_DATAOUTPUTITEMS = 'dataoutputitems';
const GD_URLPARAM_DATAOUTPUTITEMS_META = 'meta';
const GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS = 'datasetmodulesettings';
const GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA = 'moduledata';
const GD_URLPARAM_DATAOUTPUTITEMS_DATABASES = 'dbData';
const GD_URLPARAM_DATAOUTPUTITEMS_SESSION = 'session';

const GD_URLPARAM_DATASOURCES = 'datasource';
const GD_URLPARAM_DATASOURCES_ONLYMODEL = 'onlymodel';
const GD_URLPARAM_DATASOURCES_MODELANDREQUEST = 'modelandrequest';

const GD_URLPARAM_EXTRAROUTES = 'extraroutes';
const POP_JS_MULTIPLEROUTES = 'multiple-routes';

const GD_URLPARAM_STOPFETCHING = 'stop-fetching';
