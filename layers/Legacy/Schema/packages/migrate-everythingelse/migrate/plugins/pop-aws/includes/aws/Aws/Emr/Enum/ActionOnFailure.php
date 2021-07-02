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

namespace Aws\Emr\Enum;

use Aws\Common\Enum;

/**
 * Contains enumerable ActionOnFailure values
 */
class ActionOnFailure extends Enum
{
    const TERMINATE_JOB_FLOW = 'TERMINATE_JOB_FLOW';
    const TERMINATE_CLUSTER = 'TERMINATE_CLUSTER';
    const CANCEL_AND_WAIT = 'CANCEL_AND_WAIT';
    const CONTINUE_JOB_FLOW = 'CONTINUE';
}
