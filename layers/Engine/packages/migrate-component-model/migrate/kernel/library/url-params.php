<?php

























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
