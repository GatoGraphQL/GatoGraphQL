<?php

return array (
    'apiVersion' => '2014-03-28',
    'endpointPrefix' => 'logs',
    'serviceFullName' => 'Amazon CloudWatch Logs',
    'serviceType' => 'json',
    'jsonVersion' => '1.1',
    'targetPrefix' => 'Logs_20140328.',
    'signatureVersion' => 'v4',
    'namespace' => 'CloudWatchLogs',
    'regions' => array(
        'us-east-1' => array(
            'http' => false,
            'https' => true,
            'hostname' => 'logs.us-east-1.amazonaws.com',
        ),
    ),
    'operations' => array(
        'CancelExportTask' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.CancelExportTask',
                ),
                'taskId' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the operation is not valid on the specified resource',
                    'class' => 'InvalidOperationException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'CreateExportTask' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'CreateExportTaskResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.CreateExportTask',
                ),
                'taskName' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logStreamNamePrefix' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'from' => array(
                    'required' => true,
                    'type' => 'numeric',
                    'location' => 'json',
                ),
                'to' => array(
                    'required' => true,
                    'type' => 'numeric',
                    'location' => 'json',
                ),
                'destination' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'destinationPrefix' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if you have reached the maximum number of resources that can be created.',
                    'class' => 'LimitExceededException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the specified resource already exists.',
                    'class' => 'ResourceAlreadyExistsException',
                ),
            ),
        ),
        'CreateLogGroup' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.CreateLogGroup',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource already exists.',
                    'class' => 'ResourceAlreadyExistsException',
                ),
                array(
                    'reason' => 'Returned if you have reached the maximum number of resources that can be created.',
                    'class' => 'LimitExceededException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'CreateLogStream' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.CreateLogStream',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logStreamName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource already exists.',
                    'class' => 'ResourceAlreadyExistsException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DeleteDestination' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DeleteDestination',
                ),
                'destinationName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DeleteLogGroup' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DeleteLogGroup',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DeleteLogStream' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DeleteLogStream',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logStreamName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DeleteMetricFilter' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DeleteMetricFilter',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DeleteRetentionPolicy' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DeleteRetentionPolicy',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DeleteSubscriptionFilter' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DeleteSubscriptionFilter',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DescribeDestinations' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeDestinationsResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DescribeDestinations',
                ),
                'DestinationNamePrefix' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 50,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DescribeExportTasks' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeExportTasksResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DescribeExportTasks',
                ),
                'taskId' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'statusCode' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 50,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DescribeLogGroups' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeLogGroupsResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DescribeLogGroups',
                ),
                'logGroupNamePrefix' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 50,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DescribeLogStreams' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeLogStreamsResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DescribeLogStreams',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logStreamNamePrefix' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'orderBy' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
                'descending' => array(
                    'type' => 'boolean',
                    'format' => 'boolean-string',
                    'location' => 'json',
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 50,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DescribeMetricFilters' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeMetricFiltersResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DescribeMetricFilters',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterNamePrefix' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 50,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'DescribeSubscriptionFilters' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeSubscriptionFiltersResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.DescribeSubscriptionFilters',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterNamePrefix' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 50,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'FilterLogEvents' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'FilterLogEventsResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.FilterLogEvents',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logStreamNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'minItems' => 1,
                    'maxItems' => 100,
                    'items' => array(
                        'name' => 'LogStreamName',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
                'startTime' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                ),
                'endTime' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                ),
                'filterPattern' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 10000,
                ),
                'interleaved' => array(
                    'type' => 'boolean',
                    'format' => 'boolean-string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'GetLogEvents' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'GetLogEventsResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.GetLogEvents',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logStreamName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'startTime' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                ),
                'endTime' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'minimum' => 1,
                    'maximum' => 10000,
                ),
                'startFromHead' => array(
                    'type' => 'boolean',
                    'format' => 'boolean-string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'PutDestination' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'PutDestinationResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.PutDestination',
                ),
                'destinationName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'targetArn' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'roleArn' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'PutDestinationPolicy' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.PutDestinationPolicy',
                ),
                'destinationName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'accessPolicy' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'PutLogEvents' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'PutLogEventsResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.PutLogEvents',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logStreamName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'logEvents' => array(
                    'required' => true,
                    'type' => 'array',
                    'location' => 'json',
                    'minItems' => 1,
                    'maxItems' => 10000,
                    'items' => array(
                        'name' => 'InputLogEvent',
                        'type' => 'object',
                        'properties' => array(
                            'timestamp' => array(
                                'required' => true,
                                'type' => 'numeric',
                            ),
                            'message' => array(
                                'required' => true,
                                'type' => 'string',
                                'minLength' => 1,
                            ),
                        ),
                    ),
                ),
                'sequenceToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'class' => 'InvalidSequenceTokenException',
                ),
                array(
                    'class' => 'DataAlreadyAcceptedException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'PutMetricFilter' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.PutMetricFilter',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterPattern' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ),
                'metricTransformations' => array(
                    'required' => true,
                    'type' => 'array',
                    'location' => 'json',
                    'minItems' => 1,
                    'maxItems' => 1,
                    'items' => array(
                        'name' => 'MetricTransformation',
                        'type' => 'object',
                        'properties' => array(
                            'metricName' => array(
                                'required' => true,
                                'type' => 'string',
                            ),
                            'metricNamespace' => array(
                                'required' => true,
                                'type' => 'string',
                            ),
                            'metricValue' => array(
                                'required' => true,
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if you have reached the maximum number of resources that can be created.',
                    'class' => 'LimitExceededException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'PutRetentionPolicy' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.PutRetentionPolicy',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'retentionInDays' => array(
                    'required' => true,
                    'type' => 'numeric',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'PutSubscriptionFilter' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'EmptyOutput',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.PutSubscriptionFilter',
                ),
                'logGroupName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'filterPattern' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ),
                'destinationArn' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'roleArn' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the specified resource does not exist.',
                    'class' => 'ResourceNotFoundException',
                ),
                array(
                    'reason' => 'Returned if multiple requests to update the same resource were in conflict.',
                    'class' => 'OperationAbortedException',
                ),
                array(
                    'reason' => 'Returned if you have reached the maximum number of resources that can be created.',
                    'class' => 'LimitExceededException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
        'TestMetricFilter' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'TestMetricFilterResponse',
            'responseType' => 'model',
            'parameters' => array(
                'Content-Type' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'application/x-amz-json-1.1',
                ),
                'command.expects' => array(
                    'static' => true,
                    'default' => 'application/json',
                ),
                'X-Amz-Target' => array(
                    'static' => true,
                    'location' => 'header',
                    'default' => 'Logs_20140328.TestMetricFilter',
                ),
                'filterPattern' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ),
                'logEventMessages' => array(
                    'required' => true,
                    'type' => 'array',
                    'location' => 'json',
                    'minItems' => 1,
                    'maxItems' => 50,
                    'items' => array(
                        'name' => 'EventMessage',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'Returned if a parameter of the request is incorrectly specified.',
                    'class' => 'InvalidParameterException',
                ),
                array(
                    'reason' => 'Returned if the service cannot complete the request.',
                    'class' => 'ServiceUnavailableException',
                ),
            ),
        ),
    ),
    'models' => array(
        'EmptyOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
        ),
        'CreateExportTaskResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'taskId' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeDestinationsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'destinations' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'Destination',
                        'type' => 'object',
                        'properties' => array(
                            'destinationName' => array(
                                'type' => 'string',
                            ),
                            'targetArn' => array(
                                'type' => 'string',
                            ),
                            'roleArn' => array(
                                'type' => 'string',
                            ),
                            'accessPolicy' => array(
                                'type' => 'string',
                            ),
                            'arn' => array(
                                'type' => 'string',
                            ),
                            'creationTime' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeExportTasksResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'exportTasks' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ExportTask',
                        'type' => 'object',
                        'properties' => array(
                            'taskId' => array(
                                'type' => 'string',
                            ),
                            'taskName' => array(
                                'type' => 'string',
                            ),
                            'logGroupName' => array(
                                'type' => 'string',
                            ),
                            'from' => array(
                                'type' => 'numeric',
                            ),
                            'to' => array(
                                'type' => 'numeric',
                            ),
                            'destination' => array(
                                'type' => 'string',
                            ),
                            'destinationPrefix' => array(
                                'type' => 'string',
                            ),
                            'status' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'code' => array(
                                        'type' => 'string',
                                    ),
                                    'message' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'executionInfo' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'creationTime' => array(
                                        'type' => 'numeric',
                                    ),
                                    'completionTime' => array(
                                        'type' => 'numeric',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeLogGroupsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'logGroups' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'LogGroup',
                        'type' => 'object',
                        'properties' => array(
                            'logGroupName' => array(
                                'type' => 'string',
                            ),
                            'creationTime' => array(
                                'type' => 'numeric',
                            ),
                            'retentionInDays' => array(
                                'type' => 'numeric',
                            ),
                            'metricFilterCount' => array(
                                'type' => 'numeric',
                            ),
                            'arn' => array(
                                'type' => 'string',
                            ),
                            'storedBytes' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeLogStreamsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'logStreams' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'LogStream',
                        'type' => 'object',
                        'properties' => array(
                            'logStreamName' => array(
                                'type' => 'string',
                            ),
                            'creationTime' => array(
                                'type' => 'numeric',
                            ),
                            'firstEventTimestamp' => array(
                                'type' => 'numeric',
                            ),
                            'lastEventTimestamp' => array(
                                'type' => 'numeric',
                            ),
                            'lastIngestionTime' => array(
                                'type' => 'numeric',
                            ),
                            'uploadSequenceToken' => array(
                                'type' => 'string',
                            ),
                            'arn' => array(
                                'type' => 'string',
                            ),
                            'storedBytes' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeMetricFiltersResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'metricFilters' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'MetricFilter',
                        'type' => 'object',
                        'properties' => array(
                            'filterName' => array(
                                'type' => 'string',
                            ),
                            'filterPattern' => array(
                                'type' => 'string',
                            ),
                            'metricTransformations' => array(
                                'type' => 'array',
                                'items' => array(
                                    'name' => 'MetricTransformation',
                                    'type' => 'object',
                                    'properties' => array(
                                        'metricName' => array(
                                            'type' => 'string',
                                        ),
                                        'metricNamespace' => array(
                                            'type' => 'string',
                                        ),
                                        'metricValue' => array(
                                            'type' => 'string',
                                        ),
                                    ),
                                ),
                            ),
                            'creationTime' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeSubscriptionFiltersResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'subscriptionFilters' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'SubscriptionFilter',
                        'type' => 'object',
                        'properties' => array(
                            'filterName' => array(
                                'type' => 'string',
                            ),
                            'logGroupName' => array(
                                'type' => 'string',
                            ),
                            'filterPattern' => array(
                                'type' => 'string',
                            ),
                            'destinationArn' => array(
                                'type' => 'string',
                            ),
                            'roleArn' => array(
                                'type' => 'string',
                            ),
                            'creationTime' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'FilterLogEventsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'events' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'FilteredLogEvent',
                        'type' => 'object',
                        'properties' => array(
                            'logStreamName' => array(
                                'type' => 'string',
                            ),
                            'timestamp' => array(
                                'type' => 'numeric',
                            ),
                            'message' => array(
                                'type' => 'string',
                            ),
                            'ingestionTime' => array(
                                'type' => 'numeric',
                            ),
                            'eventId' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'searchedLogStreams' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'SearchedLogStream',
                        'type' => 'object',
                        'properties' => array(
                            'logStreamName' => array(
                                'type' => 'string',
                            ),
                            'searchedCompletely' => array(
                                'type' => 'boolean',
                            ),
                        ),
                    ),
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'GetLogEventsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'events' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'OutputLogEvent',
                        'type' => 'object',
                        'properties' => array(
                            'timestamp' => array(
                                'type' => 'numeric',
                            ),
                            'message' => array(
                                'type' => 'string',
                            ),
                            'ingestionTime' => array(
                                'type' => 'numeric',
                            ),
                        ),
                    ),
                ),
                'nextForwardToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
                'nextBackwardToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'PutDestinationResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'destination' => array(
                    'type' => 'object',
                    'location' => 'json',
                    'properties' => array(
                        'destinationName' => array(
                            'type' => 'string',
                        ),
                        'targetArn' => array(
                            'type' => 'string',
                        ),
                        'roleArn' => array(
                            'type' => 'string',
                        ),
                        'accessPolicy' => array(
                            'type' => 'string',
                        ),
                        'arn' => array(
                            'type' => 'string',
                        ),
                        'creationTime' => array(
                            'type' => 'numeric',
                        ),
                    ),
                ),
            ),
        ),
        'PutLogEventsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'nextSequenceToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
                'rejectedLogEventsInfo' => array(
                    'type' => 'object',
                    'location' => 'json',
                    'properties' => array(
                        'tooNewLogEventStartIndex' => array(
                            'type' => 'numeric',
                        ),
                        'tooOldLogEventEndIndex' => array(
                            'type' => 'numeric',
                        ),
                        'expiredLogEventEndIndex' => array(
                            'type' => 'numeric',
                        ),
                    ),
                ),
            ),
        ),
        'TestMetricFilterResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'matches' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'MetricFilterMatchRecord',
                        'type' => 'object',
                        'properties' => array(
                            'eventNumber' => array(
                                'type' => 'numeric',
                            ),
                            'eventMessage' => array(
                                'type' => 'string',
                            ),
                            'extractedValues' => array(
                                'type' => 'object',
                                'additionalProperties' => array(
                                    'type' => 'string',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'iterators' => array(
        'DescribeDestinations' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextToken',
            'limit_key' => 'limit',
            'result_key' => 'destinations',
        ),
        'DescribeLogGroups' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextToken',
            'limit_key' => 'limit',
            'result_key' => 'logGroups',
        ),
        'DescribeLogStreams' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextToken',
            'limit_key' => 'limit',
            'result_key' => 'logStreams',
        ),
        'DescribeMetricFilters' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextToken',
            'limit_key' => 'limit',
            'result_key' => 'metricFilters',
        ),
        'DescribeSubscriptionFilters' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextToken',
            'limit_key' => 'limit',
            'result_key' => 'subscriptionFilters',
        ),
        'FilterLogEvents' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextToken',
            'limit_key' => 'limit',
            'result_key' => array(
                'events',
                'searchedLogStreams',
            ),
        ),
        'GetLogEvents' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextForwardToken',
            'limit_key' => 'limit',
            'result_key' => 'events',
        ),
    ),
);
