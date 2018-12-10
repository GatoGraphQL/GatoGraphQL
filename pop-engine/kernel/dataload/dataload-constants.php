<?php

define ('POP_DATALOAD_DATASOURCE_IMMUTABLE', 'immutable');
define ('POP_DATALOAD_DATASOURCE_MUTABLEONMODEL', 'mutableonmodel');
define ('POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST', 'mutableonrequest');

define ('GD_DATALOAD_CHECKPOINTS', 'checkpoints');
define ('GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS', 'actionexecution-checkpoints');

// These 2 types are needed basically to tell if the block in the front-end needs to refetch the data from the server or not.
// Eg: non-loggedin user checks My Events block, gives error, then logs in, then goes back to My Events, it must refetch the "my events" data
// But this doesn't happen with "Create new Event", there's nothing to refetch in this case
define ('GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC', 'static');
define ('GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER', 'datafromserver');
define ('GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS', 'stateless');

