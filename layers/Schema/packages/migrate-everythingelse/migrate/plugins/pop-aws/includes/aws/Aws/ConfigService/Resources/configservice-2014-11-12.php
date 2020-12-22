<?php
/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

return array (
    'apiVersion' => '2014-11-12',
    'endpointPrefix' => 'config',
    'serviceFullName' => 'AWS Config',
    'serviceAbbreviation' => 'Config Service',
    'serviceType' => 'json',
    'jsonVersion' => '1.1',
    'targetPrefix' => 'StarlingDoveService.',
    'signatureVersion' => 'v4',
    'namespace' => 'ConfigService',
    'operations' => array(
        'DeleteConfigRule' => array(
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
                    'default' => 'StarlingDoveService.DeleteConfigRule',
                ),
                'ConfigRuleName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more AWS Config rules in the request are invalid. Verify that the rule names are correct and try again.',
                    'class' => 'NoSuchConfigRuleException',
                ),
            ),
        ),
        'DeleteDeliveryChannel' => array(
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
                    'default' => 'StarlingDoveService.DeleteDeliveryChannel',
                ),
                'DeliveryChannelName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a delivery channel that does not exist.',
                    'class' => 'NoSuchDeliveryChannelException',
                ),
                array(
                    'reason' => 'You cannot delete the delivery channel you specified because the configuration recorder is running.',
                    'class' => 'LastDeliveryChannelDeleteFailedException',
                ),
            ),
        ),
        'DeliverConfigSnapshot' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DeliverConfigSnapshotResponse',
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
                    'default' => 'StarlingDoveService.DeliverConfigSnapshot',
                ),
                'deliveryChannelName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a delivery channel that does not exist.',
                    'class' => 'NoSuchDeliveryChannelException',
                ),
                array(
                    'reason' => 'There are no configuration recorders available to provide the role needed to describe your resources. Create a configuration recorder.',
                    'class' => 'NoAvailableConfigurationRecorderException',
                ),
                array(
                    'reason' => 'There is no configuration recorder running.',
                    'class' => 'NoRunningConfigurationRecorderException',
                ),
            ),
        ),
        'DescribeComplianceByConfigRule' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeComplianceByConfigRuleResponse',
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
                    'default' => 'StarlingDoveService.DescribeComplianceByConfigRule',
                ),
                'ConfigRuleNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 25,
                    'items' => array(
                        'name' => 'StringWithCharLimit64',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
                'ComplianceTypes' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 3,
                    'items' => array(
                        'name' => 'ComplianceType',
                        'type' => 'string',
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more of the specified parameters are invalid. Verify that your parameters are valid and try again.',
                    'class' => 'InvalidParameterValueException',
                ),
                array(
                    'reason' => 'One or more AWS Config rules in the request are invalid. Verify that the rule names are correct and try again.',
                    'class' => 'NoSuchConfigRuleException',
                ),
            ),
        ),
        'DescribeComplianceByResource' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeComplianceByResourceResponse',
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
                    'default' => 'StarlingDoveService.DescribeComplianceByResource',
                ),
                'ResourceType' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'ResourceId' => array(
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'ComplianceTypes' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 3,
                    'items' => array(
                        'name' => 'ComplianceType',
                        'type' => 'string',
                    ),
                ),
                'Limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'maximum' => 100,
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more of the specified parameters are invalid. Verify that your parameters are valid and try again.',
                    'class' => 'InvalidParameterValueException',
                ),
                array(
                    'reason' => 'The specified next token is invalid. Specify the nextToken string that was returned in the previous response to get the next page of results.',
                    'class' => 'InvalidNextTokenException',
                ),
            ),
        ),
        'DescribeConfigRuleEvaluationStatus' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeConfigRuleEvaluationStatusResponse',
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
                    'default' => 'StarlingDoveService.DescribeConfigRuleEvaluationStatus',
                ),
                'ConfigRuleNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 25,
                    'items' => array(
                        'name' => 'StringWithCharLimit64',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more AWS Config rules in the request are invalid. Verify that the rule names are correct and try again.',
                    'class' => 'NoSuchConfigRuleException',
                ),
            ),
        ),
        'DescribeConfigRules' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeConfigRulesResponse',
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
                    'default' => 'StarlingDoveService.DescribeConfigRules',
                ),
                'ConfigRuleNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 25,
                    'items' => array(
                        'name' => 'StringWithCharLimit64',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more AWS Config rules in the request are invalid. Verify that the rule names are correct and try again.',
                    'class' => 'NoSuchConfigRuleException',
                ),
            ),
        ),
        'DescribeConfigurationRecorderStatus' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeConfigurationRecorderStatusResponse',
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
                    'default' => 'StarlingDoveService.DescribeConfigurationRecorderStatus',
                ),
                'ConfigurationRecorderNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'RecorderName',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a configuration recorder that does not exist.',
                    'class' => 'NoSuchConfigurationRecorderException',
                ),
            ),
        ),
        'DescribeConfigurationRecorders' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeConfigurationRecordersResponse',
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
                    'default' => 'StarlingDoveService.DescribeConfigurationRecorders',
                ),
                'ConfigurationRecorderNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'RecorderName',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a configuration recorder that does not exist.',
                    'class' => 'NoSuchConfigurationRecorderException',
                ),
            ),
        ),
        'DescribeDeliveryChannelStatus' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeDeliveryChannelStatusResponse',
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
                    'default' => 'StarlingDoveService.DescribeDeliveryChannelStatus',
                ),
                'DeliveryChannelNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ChannelName',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a delivery channel that does not exist.',
                    'class' => 'NoSuchDeliveryChannelException',
                ),
            ),
        ),
        'DescribeDeliveryChannels' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'DescribeDeliveryChannelsResponse',
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
                    'default' => 'StarlingDoveService.DescribeDeliveryChannels',
                ),
                'DeliveryChannelNames' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ChannelName',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a delivery channel that does not exist.',
                    'class' => 'NoSuchDeliveryChannelException',
                ),
            ),
        ),
        'GetComplianceDetailsByConfigRule' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'GetComplianceDetailsByConfigRuleResponse',
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
                    'default' => 'StarlingDoveService.GetComplianceDetailsByConfigRule',
                ),
                'ConfigRuleName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'ComplianceTypes' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 3,
                    'items' => array(
                        'name' => 'ComplianceType',
                        'type' => 'string',
                    ),
                ),
                'Limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'maximum' => 100,
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more of the specified parameters are invalid. Verify that your parameters are valid and try again.',
                    'class' => 'InvalidParameterValueException',
                ),
                array(
                    'reason' => 'The specified next token is invalid. Specify the nextToken string that was returned in the previous response to get the next page of results.',
                    'class' => 'InvalidNextTokenException',
                ),
                array(
                    'reason' => 'One or more AWS Config rules in the request are invalid. Verify that the rule names are correct and try again.',
                    'class' => 'NoSuchConfigRuleException',
                ),
            ),
        ),
        'GetComplianceDetailsByResource' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'GetComplianceDetailsByResourceResponse',
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
                    'default' => 'StarlingDoveService.GetComplianceDetailsByResource',
                ),
                'ResourceType' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'ResourceId' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
                'ComplianceTypes' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 3,
                    'items' => array(
                        'name' => 'ComplianceType',
                        'type' => 'string',
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more of the specified parameters are invalid. Verify that your parameters are valid and try again.',
                    'class' => 'InvalidParameterValueException',
                ),
            ),
        ),
        'GetComplianceSummaryByConfigRule' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'GetComplianceSummaryByConfigRuleResponse',
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
                    'default' => 'StarlingDoveService.GetComplianceSummaryByConfigRule',
                ),
            ),
        ),
        'GetComplianceSummaryByResourceType' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'GetComplianceSummaryByResourceTypeResponse',
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
                    'default' => 'StarlingDoveService.GetComplianceSummaryByResourceType',
                ),
                'ResourceTypes' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 100,
                    'items' => array(
                        'name' => 'StringWithCharLimit256',
                        'type' => 'string',
                        'minLength' => 1,
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more of the specified parameters are invalid. Verify that your parameters are valid and try again.',
                    'class' => 'InvalidParameterValueException',
                ),
            ),
        ),
        'GetResourceConfigHistory' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'GetResourceConfigHistoryResponse',
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
                    'default' => 'StarlingDoveService.GetResourceConfigHistory',
                ),
                'resourceType' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ),
                'resourceId' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ),
                'laterTime' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time',
                    'location' => 'json',
                ),
                'earlierTime' => array(
                    'type' => array(
                        'object',
                        'string',
                        'integer',
                    ),
                    'format' => 'date-time',
                    'location' => 'json',
                ),
                'chronologicalOrder' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'maximum' => 100,
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The requested action is not valid.',
                    'class' => 'ValidationException',
                ),
                array(
                    'reason' => 'The specified time range is not valid. The earlier time is not chronologically before the later time.',
                    'class' => 'InvalidTimeRangeException',
                ),
                array(
                    'reason' => 'The specified limit is outside the allowable range.',
                    'class' => 'InvalidLimitException',
                ),
                array(
                    'reason' => 'The specified next token is invalid. Specify the nextToken string that was returned in the previous response to get the next page of results.',
                    'class' => 'InvalidNextTokenException',
                ),
                array(
                    'reason' => 'There are no configuration recorders available to provide the role needed to describe your resources. Create a configuration recorder.',
                    'class' => 'NoAvailableConfigurationRecorderException',
                ),
                array(
                    'reason' => 'You have specified a resource that is either unknown or has not been discovered.',
                    'class' => 'ResourceNotDiscoveredException',
                ),
            ),
        ),
        'ListDiscoveredResources' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'ListDiscoveredResourcesResponse',
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
                    'default' => 'StarlingDoveService.ListDiscoveredResources',
                ),
                'resourceType' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ),
                'resourceIds' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ResourceId',
                        'type' => 'string',
                    ),
                ),
                'resourceName' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
                'limit' => array(
                    'type' => 'numeric',
                    'location' => 'json',
                    'maximum' => 100,
                ),
                'includeDeletedResources' => array(
                    'type' => 'boolean',
                    'format' => 'boolean-string',
                    'location' => 'json',
                ),
                'nextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'The requested action is not valid.',
                    'class' => 'ValidationException',
                ),
                array(
                    'reason' => 'The specified limit is outside the allowable range.',
                    'class' => 'InvalidLimitException',
                ),
                array(
                    'reason' => 'The specified next token is invalid. Specify the nextToken string that was returned in the previous response to get the next page of results.',
                    'class' => 'InvalidNextTokenException',
                ),
                array(
                    'reason' => 'There are no configuration recorders available to provide the role needed to describe your resources. Create a configuration recorder.',
                    'class' => 'NoAvailableConfigurationRecorderException',
                ),
            ),
        ),
        'PutConfigRule' => array(
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
                    'default' => 'StarlingDoveService.PutConfigRule',
                ),
                'ConfigRule' => array(
                    'required' => true,
                    'type' => 'object',
                    'location' => 'json',
                    'properties' => array(
                        'ConfigRuleName' => array(
                            'type' => 'string',
                            'minLength' => 1,
                        ),
                        'ConfigRuleArn' => array(
                            'type' => 'string',
                        ),
                        'ConfigRuleId' => array(
                            'type' => 'string',
                        ),
                        'Description' => array(
                            'type' => 'string',
                        ),
                        'Scope' => array(
                            'type' => 'object',
                            'properties' => array(
                                'ComplianceResourceTypes' => array(
                                    'type' => 'array',
                                    'maxItems' => 100,
                                    'items' => array(
                                        'name' => 'StringWithCharLimit256',
                                        'type' => 'string',
                                        'minLength' => 1,
                                    ),
                                ),
                                'TagKey' => array(
                                    'type' => 'string',
                                    'minLength' => 1,
                                ),
                                'TagValue' => array(
                                    'type' => 'string',
                                    'minLength' => 1,
                                ),
                                'ComplianceResourceId' => array(
                                    'type' => 'string',
                                    'minLength' => 1,
                                ),
                            ),
                        ),
                        'Source' => array(
                            'required' => true,
                            'type' => 'object',
                            'properties' => array(
                                'Owner' => array(
                                    'type' => 'string',
                                ),
                                'SourceIdentifier' => array(
                                    'type' => 'string',
                                    'minLength' => 1,
                                ),
                                'SourceDetails' => array(
                                    'type' => 'array',
                                    'maxItems' => 25,
                                    'items' => array(
                                        'name' => 'SourceDetail',
                                        'type' => 'object',
                                        'properties' => array(
                                            'EventSource' => array(
                                                'type' => 'string',
                                            ),
                                            'MessageType' => array(
                                                'type' => 'string',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                        'InputParameters' => array(
                            'type' => 'string',
                            'minLength' => 1,
                        ),
                        'MaximumExecutionFrequency' => array(
                            'type' => 'string',
                        ),
                        'ConfigRuleState' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more of the specified parameters are invalid. Verify that your parameters are valid and try again.',
                    'class' => 'InvalidParameterValueException',
                ),
                array(
                    'reason' => 'Failed to add the AWS Config rule because the account already contains the maximum number of 25 rules. Consider deleting any deactivated rules before adding new rules.',
                    'class' => 'MaxNumberOfConfigRulesExceededException',
                ),
                array(
                    'reason' => 'The rule is currently being deleted. Wait for a while and try again.',
                    'class' => 'ResourceInUseException',
                ),
            ),
        ),
        'PutConfigurationRecorder' => array(
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
                    'default' => 'StarlingDoveService.PutConfigurationRecorder',
                ),
                'ConfigurationRecorder' => array(
                    'required' => true,
                    'type' => 'object',
                    'location' => 'json',
                    'properties' => array(
                        'name' => array(
                            'type' => 'string',
                            'minLength' => 1,
                        ),
                        'roleARN' => array(
                            'type' => 'string',
                        ),
                        'recordingGroup' => array(
                            'type' => 'object',
                            'properties' => array(
                                'allSupported' => array(
                                    'type' => 'boolean',
                                    'format' => 'boolean-string',
                                ),
                                'resourceTypes' => array(
                                    'type' => 'array',
                                    'items' => array(
                                        'name' => 'ResourceType',
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have reached the limit on the number of recorders you can create.',
                    'class' => 'MaxNumberOfConfigurationRecordersExceededException',
                ),
                array(
                    'reason' => 'You have provided a configuration recorder name that is not valid.',
                    'class' => 'InvalidConfigurationRecorderNameException',
                ),
                array(
                    'reason' => 'You have provided a null or empty role ARN.',
                    'class' => 'InvalidRoleException',
                ),
                array(
                    'reason' => 'AWS Config throws an exception if the recording group does not contain a valid list of resource types. Invalid values could also be incorrectly formatted.',
                    'class' => 'InvalidRecordingGroupException',
                ),
            ),
        ),
        'PutDeliveryChannel' => array(
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
                    'default' => 'StarlingDoveService.PutDeliveryChannel',
                ),
                'DeliveryChannel' => array(
                    'required' => true,
                    'type' => 'object',
                    'location' => 'json',
                    'properties' => array(
                        'name' => array(
                            'type' => 'string',
                            'minLength' => 1,
                        ),
                        's3BucketName' => array(
                            'type' => 'string',
                        ),
                        's3KeyPrefix' => array(
                            'type' => 'string',
                        ),
                        'snsTopicARN' => array(
                            'type' => 'string',
                        ),
                        'configSnapshotDeliveryProperties' => array(
                            'type' => 'object',
                            'properties' => array(
                                'deliveryFrequency' => array(
                                    'type' => 'string',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have reached the limit on the number of delivery channels you can create.',
                    'class' => 'MaxNumberOfDeliveryChannelsExceededException',
                ),
                array(
                    'reason' => 'There are no configuration recorders available to provide the role needed to describe your resources. Create a configuration recorder.',
                    'class' => 'NoAvailableConfigurationRecorderException',
                ),
                array(
                    'reason' => 'The specified delivery channel name is not valid.',
                    'class' => 'InvalidDeliveryChannelNameException',
                ),
                array(
                    'reason' => 'The specified Amazon S3 bucket does not exist.',
                    'class' => 'NoSuchBucketException',
                ),
                array(
                    'reason' => 'The specified Amazon S3 key prefix is not valid.',
                    'class' => 'InvalidS3KeyPrefixException',
                ),
                array(
                    'reason' => 'The specified Amazon SNS topic does not exist.',
                    'class' => 'InvalidSNSTopicARNException',
                ),
                array(
                    'reason' => 'Your Amazon S3 bucket policy does not permit AWS Config to write to it.',
                    'class' => 'InsufficientDeliveryPolicyException',
                ),
            ),
        ),
        'PutEvaluations' => array(
            'httpMethod' => 'POST',
            'uri' => '/',
            'class' => 'Aws\\Common\\Command\\JsonCommand',
            'responseClass' => 'PutEvaluationsResponse',
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
                    'default' => 'StarlingDoveService.PutEvaluations',
                ),
                'Evaluations' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'maxItems' => 100,
                    'items' => array(
                        'name' => 'Evaluation',
                        'type' => 'object',
                        'properties' => array(
                            'ComplianceResourceType' => array(
                                'required' => true,
                                'type' => 'string',
                                'minLength' => 1,
                            ),
                            'ComplianceResourceId' => array(
                                'required' => true,
                                'type' => 'string',
                                'minLength' => 1,
                            ),
                            'ComplianceType' => array(
                                'required' => true,
                                'type' => 'string',
                            ),
                            'Annotation' => array(
                                'type' => 'string',
                                'minLength' => 1,
                            ),
                            'OrderingTimestamp' => array(
                                'required' => true,
                                'type' => array(
                                    'object',
                                    'string',
                                    'integer',
                                ),
                                'format' => 'date-time',
                            ),
                        ),
                    ),
                ),
                'ResultToken' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'One or more of the specified parameters are invalid. Verify that your parameters are valid and try again.',
                    'class' => 'InvalidParameterValueException',
                ),
                array(
                    'reason' => 'The result token is invalid.',
                    'class' => 'InvalidResultTokenException',
                ),
                array(
                    'reason' => 'One or more AWS Config rules in the request are invalid. Verify that the rule names are correct and try again.',
                    'class' => 'NoSuchConfigRuleException',
                ),
            ),
        ),
        'StartConfigurationRecorder' => array(
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
                    'default' => 'StarlingDoveService.StartConfigurationRecorder',
                ),
                'ConfigurationRecorderName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a configuration recorder that does not exist.',
                    'class' => 'NoSuchConfigurationRecorderException',
                ),
                array(
                    'reason' => 'There is no delivery channel available to record configurations.',
                    'class' => 'NoAvailableDeliveryChannelException',
                ),
            ),
        ),
        'StopConfigurationRecorder' => array(
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
                    'default' => 'StarlingDoveService.StopConfigurationRecorder',
                ),
                'ConfigurationRecorderName' => array(
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'minLength' => 1,
                ),
            ),
            'errorResponses' => array(
                array(
                    'reason' => 'You have specified a configuration recorder that does not exist.',
                    'class' => 'NoSuchConfigurationRecorderException',
                ),
            ),
        ),
    ),
    'models' => array(
        'EmptyOutput' => array(
            'type' => 'object',
            'additionalProperties' => true,
        ),
        'DeliverConfigSnapshotResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'configSnapshotId' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeComplianceByConfigRuleResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ComplianceByConfigRules' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ComplianceByConfigRule',
                        'type' => 'object',
                        'properties' => array(
                            'ConfigRuleName' => array(
                                'type' => 'string',
                            ),
                            'Compliance' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'ComplianceType' => array(
                                        'type' => 'string',
                                    ),
                                    'ComplianceContributorCount' => array(
                                        'type' => 'object',
                                        'properties' => array(
                                            'CappedCount' => array(
                                                'type' => 'numeric',
                                            ),
                                            'CapExceeded' => array(
                                                'type' => 'boolean',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeComplianceByResourceResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ComplianceByResources' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ComplianceByResource',
                        'type' => 'object',
                        'properties' => array(
                            'ResourceType' => array(
                                'type' => 'string',
                            ),
                            'ResourceId' => array(
                                'type' => 'string',
                            ),
                            'Compliance' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'ComplianceType' => array(
                                        'type' => 'string',
                                    ),
                                    'ComplianceContributorCount' => array(
                                        'type' => 'object',
                                        'properties' => array(
                                            'CappedCount' => array(
                                                'type' => 'numeric',
                                            ),
                                            'CapExceeded' => array(
                                                'type' => 'boolean',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeConfigRuleEvaluationStatusResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ConfigRulesEvaluationStatus' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ConfigRuleEvaluationStatus',
                        'type' => 'object',
                        'properties' => array(
                            'ConfigRuleName' => array(
                                'type' => 'string',
                            ),
                            'ConfigRuleArn' => array(
                                'type' => 'string',
                            ),
                            'ConfigRuleId' => array(
                                'type' => 'string',
                            ),
                            'LastSuccessfulInvocationTime' => array(
                                'type' => 'string',
                            ),
                            'LastFailedInvocationTime' => array(
                                'type' => 'string',
                            ),
                            'FirstActivatedTime' => array(
                                'type' => 'string',
                            ),
                            'LastErrorCode' => array(
                                'type' => 'string',
                            ),
                            'LastErrorMessage' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'DescribeConfigRulesResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ConfigRules' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ConfigRule',
                        'type' => 'object',
                        'properties' => array(
                            'ConfigRuleName' => array(
                                'type' => 'string',
                            ),
                            'ConfigRuleArn' => array(
                                'type' => 'string',
                            ),
                            'ConfigRuleId' => array(
                                'type' => 'string',
                            ),
                            'Description' => array(
                                'type' => 'string',
                            ),
                            'Scope' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'ComplianceResourceTypes' => array(
                                        'type' => 'array',
                                        'items' => array(
                                            'name' => 'StringWithCharLimit256',
                                            'type' => 'string',
                                        ),
                                    ),
                                    'TagKey' => array(
                                        'type' => 'string',
                                    ),
                                    'TagValue' => array(
                                        'type' => 'string',
                                    ),
                                    'ComplianceResourceId' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'Source' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'Owner' => array(
                                        'type' => 'string',
                                    ),
                                    'SourceIdentifier' => array(
                                        'type' => 'string',
                                    ),
                                    'SourceDetails' => array(
                                        'type' => 'array',
                                        'items' => array(
                                            'name' => 'SourceDetail',
                                            'type' => 'object',
                                            'properties' => array(
                                                'EventSource' => array(
                                                    'type' => 'string',
                                                ),
                                                'MessageType' => array(
                                                    'type' => 'string',
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'InputParameters' => array(
                                'type' => 'string',
                            ),
                            'MaximumExecutionFrequency' => array(
                                'type' => 'string',
                            ),
                            'ConfigRuleState' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'DescribeConfigurationRecorderStatusResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ConfigurationRecordersStatus' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ConfigurationRecorderStatus',
                        'type' => 'object',
                        'properties' => array(
                            'name' => array(
                                'type' => 'string',
                            ),
                            'lastStartTime' => array(
                                'type' => 'string',
                            ),
                            'lastStopTime' => array(
                                'type' => 'string',
                            ),
                            'recording' => array(
                                'type' => 'boolean',
                            ),
                            'lastStatus' => array(
                                'type' => 'string',
                            ),
                            'lastErrorCode' => array(
                                'type' => 'string',
                            ),
                            'lastErrorMessage' => array(
                                'type' => 'string',
                            ),
                            'lastStatusChangeTime' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'DescribeConfigurationRecordersResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ConfigurationRecorders' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ConfigurationRecorder',
                        'type' => 'object',
                        'properties' => array(
                            'name' => array(
                                'type' => 'string',
                            ),
                            'roleARN' => array(
                                'type' => 'string',
                            ),
                            'recordingGroup' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'allSupported' => array(
                                        'type' => 'boolean',
                                    ),
                                    'resourceTypes' => array(
                                        'type' => 'array',
                                        'items' => array(
                                            'name' => 'ResourceType',
                                            'type' => 'string',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'DescribeDeliveryChannelStatusResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'DeliveryChannelsStatus' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'DeliveryChannelStatus',
                        'type' => 'object',
                        'properties' => array(
                            'name' => array(
                                'type' => 'string',
                            ),
                            'configSnapshotDeliveryInfo' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'lastStatus' => array(
                                        'type' => 'string',
                                    ),
                                    'lastErrorCode' => array(
                                        'type' => 'string',
                                    ),
                                    'lastErrorMessage' => array(
                                        'type' => 'string',
                                    ),
                                    'lastAttemptTime' => array(
                                        'type' => 'string',
                                    ),
                                    'lastSuccessfulTime' => array(
                                        'type' => 'string',
                                    ),
                                    'nextDeliveryTime' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'configHistoryDeliveryInfo' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'lastStatus' => array(
                                        'type' => 'string',
                                    ),
                                    'lastErrorCode' => array(
                                        'type' => 'string',
                                    ),
                                    'lastErrorMessage' => array(
                                        'type' => 'string',
                                    ),
                                    'lastAttemptTime' => array(
                                        'type' => 'string',
                                    ),
                                    'lastSuccessfulTime' => array(
                                        'type' => 'string',
                                    ),
                                    'nextDeliveryTime' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'configStreamDeliveryInfo' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'lastStatus' => array(
                                        'type' => 'string',
                                    ),
                                    'lastErrorCode' => array(
                                        'type' => 'string',
                                    ),
                                    'lastErrorMessage' => array(
                                        'type' => 'string',
                                    ),
                                    'lastStatusChangeTime' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'DescribeDeliveryChannelsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'DeliveryChannels' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'DeliveryChannel',
                        'type' => 'object',
                        'properties' => array(
                            'name' => array(
                                'type' => 'string',
                            ),
                            's3BucketName' => array(
                                'type' => 'string',
                            ),
                            's3KeyPrefix' => array(
                                'type' => 'string',
                            ),
                            'snsTopicARN' => array(
                                'type' => 'string',
                            ),
                            'configSnapshotDeliveryProperties' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'deliveryFrequency' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'GetComplianceDetailsByConfigRuleResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'EvaluationResults' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'EvaluationResult',
                        'type' => 'object',
                        'properties' => array(
                            'EvaluationResultIdentifier' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'EvaluationResultQualifier' => array(
                                        'type' => 'object',
                                        'properties' => array(
                                            'ConfigRuleName' => array(
                                                'type' => 'string',
                                            ),
                                            'ResourceType' => array(
                                                'type' => 'string',
                                            ),
                                            'ResourceId' => array(
                                                'type' => 'string',
                                            ),
                                        ),
                                    ),
                                    'OrderingTimestamp' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'ComplianceType' => array(
                                'type' => 'string',
                            ),
                            'ResultRecordedTime' => array(
                                'type' => 'string',
                            ),
                            'ConfigRuleInvokedTime' => array(
                                'type' => 'string',
                            ),
                            'Annotation' => array(
                                'type' => 'string',
                            ),
                            'ResultToken' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'GetComplianceDetailsByResourceResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'EvaluationResults' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'EvaluationResult',
                        'type' => 'object',
                        'properties' => array(
                            'EvaluationResultIdentifier' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'EvaluationResultQualifier' => array(
                                        'type' => 'object',
                                        'properties' => array(
                                            'ConfigRuleName' => array(
                                                'type' => 'string',
                                            ),
                                            'ResourceType' => array(
                                                'type' => 'string',
                                            ),
                                            'ResourceId' => array(
                                                'type' => 'string',
                                            ),
                                        ),
                                    ),
                                    'OrderingTimestamp' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                            'ComplianceType' => array(
                                'type' => 'string',
                            ),
                            'ResultRecordedTime' => array(
                                'type' => 'string',
                            ),
                            'ConfigRuleInvokedTime' => array(
                                'type' => 'string',
                            ),
                            'Annotation' => array(
                                'type' => 'string',
                            ),
                            'ResultToken' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
                'NextToken' => array(
                    'type' => 'string',
                    'location' => 'json',
                ),
            ),
        ),
        'GetComplianceSummaryByConfigRuleResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ComplianceSummary' => array(
                    'type' => 'object',
                    'location' => 'json',
                    'properties' => array(
                        'CompliantResourceCount' => array(
                            'type' => 'object',
                            'properties' => array(
                                'CappedCount' => array(
                                    'type' => 'numeric',
                                ),
                                'CapExceeded' => array(
                                    'type' => 'boolean',
                                ),
                            ),
                        ),
                        'NonCompliantResourceCount' => array(
                            'type' => 'object',
                            'properties' => array(
                                'CappedCount' => array(
                                    'type' => 'numeric',
                                ),
                                'CapExceeded' => array(
                                    'type' => 'boolean',
                                ),
                            ),
                        ),
                        'ComplianceSummaryTimestamp' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
            ),
        ),
        'GetComplianceSummaryByResourceTypeResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'ComplianceSummariesByResourceType' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ComplianceSummaryByResourceType',
                        'type' => 'object',
                        'properties' => array(
                            'ResourceType' => array(
                                'type' => 'string',
                            ),
                            'ComplianceSummary' => array(
                                'type' => 'object',
                                'properties' => array(
                                    'CompliantResourceCount' => array(
                                        'type' => 'object',
                                        'properties' => array(
                                            'CappedCount' => array(
                                                'type' => 'numeric',
                                            ),
                                            'CapExceeded' => array(
                                                'type' => 'boolean',
                                            ),
                                        ),
                                    ),
                                    'NonCompliantResourceCount' => array(
                                        'type' => 'object',
                                        'properties' => array(
                                            'CappedCount' => array(
                                                'type' => 'numeric',
                                            ),
                                            'CapExceeded' => array(
                                                'type' => 'boolean',
                                            ),
                                        ),
                                    ),
                                    'ComplianceSummaryTimestamp' => array(
                                        'type' => 'string',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'GetResourceConfigHistoryResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'configurationItems' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ConfigurationItem',
                        'type' => 'object',
                        'properties' => array(
                            'version' => array(
                                'type' => 'string',
                            ),
                            'accountId' => array(
                                'type' => 'string',
                            ),
                            'configurationItemCaptureTime' => array(
                                'type' => 'string',
                            ),
                            'configurationItemStatus' => array(
                                'type' => 'string',
                            ),
                            'configurationStateId' => array(
                                'type' => 'string',
                            ),
                            'configurationItemMD5Hash' => array(
                                'type' => 'string',
                            ),
                            'arn' => array(
                                'type' => 'string',
                            ),
                            'resourceType' => array(
                                'type' => 'string',
                            ),
                            'resourceId' => array(
                                'type' => 'string',
                            ),
                            'resourceName' => array(
                                'type' => 'string',
                            ),
                            'awsRegion' => array(
                                'type' => 'string',
                            ),
                            'availabilityZone' => array(
                                'type' => 'string',
                            ),
                            'resourceCreationTime' => array(
                                'type' => 'string',
                            ),
                            'tags' => array(
                                'type' => 'object',
                                'additionalProperties' => array(
                                    'type' => 'string',
                                ),
                            ),
                            'relatedEvents' => array(
                                'type' => 'array',
                                'items' => array(
                                    'name' => 'RelatedEvent',
                                    'type' => 'string',
                                ),
                            ),
                            'relationships' => array(
                                'type' => 'array',
                                'items' => array(
                                    'name' => 'Relationship',
                                    'type' => 'object',
                                    'properties' => array(
                                        'resourceType' => array(
                                            'type' => 'string',
                                        ),
                                        'resourceId' => array(
                                            'type' => 'string',
                                        ),
                                        'resourceName' => array(
                                            'type' => 'string',
                                        ),
                                        'relationshipName' => array(
                                            'type' => 'string',
                                        ),
                                    ),
                                ),
                            ),
                            'configuration' => array(
                                'type' => 'string',
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
        'ListDiscoveredResourcesResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'resourceIdentifiers' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'ResourceIdentifier',
                        'type' => 'object',
                        'properties' => array(
                            'resourceType' => array(
                                'type' => 'string',
                            ),
                            'resourceId' => array(
                                'type' => 'string',
                            ),
                            'resourceName' => array(
                                'type' => 'string',
                            ),
                            'resourceDeletionTime' => array(
                                'type' => 'string',
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
        'PutEvaluationsResponse' => array(
            'type' => 'object',
            'additionalProperties' => true,
            'properties' => array(
                'FailedEvaluations' => array(
                    'type' => 'array',
                    'location' => 'json',
                    'items' => array(
                        'name' => 'Evaluation',
                        'type' => 'object',
                        'properties' => array(
                            'ComplianceResourceType' => array(
                                'type' => 'string',
                            ),
                            'ComplianceResourceId' => array(
                                'type' => 'string',
                            ),
                            'ComplianceType' => array(
                                'type' => 'string',
                            ),
                            'Annotation' => array(
                                'type' => 'string',
                            ),
                            'OrderingTimestamp' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'iterators' => array(
        'GetResourceConfigHistory' => array(
            'input_token' => 'nextToken',
            'output_token' => 'nextToken',
            'limit_key' => 'limit',
            'result_key' => 'configurationItems',
        ),
    ),
);
