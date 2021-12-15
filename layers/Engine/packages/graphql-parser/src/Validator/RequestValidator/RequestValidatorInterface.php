<?php

/**
 * Date: 10/24/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Validator\RequestValidator;

use GraphQLByPoP\GraphQLParser\Execution\Request;

interface RequestValidatorInterface
{

    public function validate(Request $request);
}
