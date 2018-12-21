<?php

const POP_DATALOAD_DATASOURCE_IMMUTABLE = 'immutable';
const POP_DATALOAD_DATASOURCE_MUTABLEONMODEL = 'mutableonmodel';
const POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST = 'mutableonrequest';

const GD_DATALOAD_CHECKPOINTS = 'checkpoints';
const GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS = 'actionexecution-checkpoints';

// These 2 types are needed basically to tell if the block in the front-end needs to refetch the data from the server or not.
// Eg: non-loggedin user checks My Events block, gives error, then logs in, then goes back to My Events, it must refetch the "my events" data
// But this doesn't happen with "Create new Event", there's nothing to refetch in this case
const GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC = 'static';
const GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER = 'datafromserver';
const GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS = 'stateless';

const GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_FUNCTIONAL = 'functional';
const GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_DBDATA = 'dbdata';

const GD_DATALOAD_FIELDPROCESSOR_FILTER = 'pop_module:dataload_fieldprocessor:%s';
