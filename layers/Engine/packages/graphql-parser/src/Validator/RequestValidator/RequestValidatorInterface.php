<?php

/**
 * Date: 10/24/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Validator\RequestValidator;

use PoP\GraphQLParser\Execution\Request;

interface RequestValidatorInterface
{

    public function validate(Request $request);
}
